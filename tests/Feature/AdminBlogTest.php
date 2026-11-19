<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\MarkdownPosts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminBlogTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret123'),
        ]);
    }

    public function test_guest_is_redirected_to_login_when_accessing_admin_routes(): void
    {
        $response = $this->get('/admin/posts');

        $response->assertRedirect(route('login'));
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('تسجيل دخول المسؤول');
    }

    public function test_admin_can_authenticate_with_valid_credentials(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ]);

        $this->assertAuthenticatedAs($this->admin);
        $response->assertRedirect(route('admin.posts.index'));
    }

    public function test_admin_cannot_authenticate_with_invalid_password(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_admin_can_view_post_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/posts');

        $response->assertStatus(200);
        $response->assertSee('إدارة مقالات المدونة');
        $response->assertSee('رفع / إضافة مقال جديد');
    }

    public function test_admin_can_create_post_via_form(): void
    {
        $testSlug = 'test-feature-article';
        $testLocale = 'ar';

        $response = $this->actingAs($this->admin)->post('/admin/posts', [
            'title' => 'مقال تجريبي جديد',
            'slug' => $testSlug,
            'locale' => $testLocale,
            'date' => '2026-11-18',
            'summary' => 'ملخص تجريبي لمقال الاختبار',
            'tags' => 'php, laravel, test',
            'body' => '## محتوى تجريبي\n\nهذا المقال تم إنشاؤه عبر الاختبار الآلي.',
        ]);

        $response->assertRedirect(route('admin.posts.index'));
        $response->assertSessionHas('status');

        $filePath = resource_path("content/posts/{$testSlug}.{$testLocale}.md");
        $this->assertFileExists($filePath);

        // تنظيف الملف بعد الاختبار
        @unlink($filePath);
    }

    public function test_admin_can_upload_markdown_file(): void
    {
        $testSlug = 'uploaded-test-post';
        $testLocale = 'ar';
        $markdownContent = "---\ntitle: \"مقال مرفوع للتجربة\"\ndate: 2026-11-18\ntags: [upload, test]\nsummary: \"تجربة رفع ملف\"\n---\n\nمتن المقال المرفوع.";

        $file = UploadedFile::fake()->createWithContent("{$testSlug}.{$testLocale}.md", $markdownContent);

        $response = $this->actingAs($this->admin)->post('/admin/posts', [
            'markdown_file' => $file,
        ]);

        $response->assertRedirect(route('admin.posts.index'));

        $filePath = resource_path("content/posts/{$testSlug}.{$testLocale}.md");
        $this->assertFileExists($filePath);

        // التحقق من ظهور المقال في المدونة
        $blogResponse = $this->get('/blog');
        $blogResponse->assertStatus(200);
        $blogResponse->assertSee('مقال مرفوع للتجربة');

        // تنظيف
        @unlink($filePath);
        app(MarkdownPosts::class)->clearCache();
    }

    public function test_admin_can_edit_post(): void
    {
        $testSlug = 'editable-test-post';
        $testLocale = 'ar';
        $filePath = resource_path("content/posts/{$testSlug}.{$testLocale}.md");

        file_put_contents($filePath, "---\ntitle: \"عنوان قديم\"\ndate: 2026-11-18\ntags: [test]\nsummary: \"ملخص\"\n---\n\nمحتوى قديم.");

        $response = $this->actingAs($this->admin)->put("/admin/posts/{$testSlug}/{$testLocale}", [
            'title' => 'عنوان محدث وجديد',
            'new_slug' => $testSlug,
            'new_locale' => $testLocale,
            'date' => '2026-11-18',
            'summary' => 'ملخص محدث',
            'tags' => 'test, updated',
            'body' => 'محتوى تم تحديثه بنجاح.',
        ]);

        $response->assertRedirect(route('admin.posts.index'));

        $updatedContent = file_get_contents($filePath);
        $this->assertStringContainsString('عنوان محدث وجديد', $updatedContent);

        @unlink($filePath);
        app(MarkdownPosts::class)->clearCache();
    }

    public function test_admin_can_delete_post(): void
    {
        $testSlug = 'deletable-test-post';
        $testLocale = 'ar';
        $filePath = resource_path("content/posts/{$testSlug}.{$testLocale}.md");

        file_put_contents($filePath, "---\ntitle: \"مقال سيتم حذفه\"\ndate: 2026-11-18\ntags: [test]\nsummary: \"حذف\"\n---\n\nمحتوى.");

        $this->assertFileExists($filePath);

        $response = $this->actingAs($this->admin)->delete("/admin/posts/{$testSlug}/{$testLocale}");

        $response->assertRedirect(route('admin.posts.index'));
        $this->assertFileDoesNotExist($filePath);

        app(MarkdownPosts::class)->clearCache();
    }

    public function test_blog_page_shows_upload_button_and_admin_login(): void
    {
        $response = $this->get('/blog');

        $response->assertStatus(200);
        $response->assertSee(route('admin.posts.create'));
        $response->assertSee(route('admin.login'));
    }

    public function test_admin_can_logout(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/logout');

        $this->assertGuest();
        $response->assertRedirect(route('blog.index'));
    }
}
