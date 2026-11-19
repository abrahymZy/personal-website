---
title: "The Complete Markdown Guide for This Blog"
date: 2026-11-10
tags: [markdown, guide, writing]
summary: "Every Markdown feature this blog engine supports, with live examples you can copy straight into your own posts."
---

This post is a practical reference for every **Markdown** feature the blog engine supports.
Treat it as a template: copy what you need and start writing.

## Basic formatting

You can write **bold** text, *italic* text, ***bold italic*** text, ~~struck~~ text,
and highlighted text using `==double equals==` so it renders like this: ==highlighted text==.

Links work too, like the [Markdown documentation](https://commonmark.org/help/), and bare URLs
are detected automatically: https://commonmark.org

> Blockquotes render with a coloured side bar. They suit key ideas or literal quotations.
>
> And they can span more than one paragraph.

---

## Lists

An unordered list:

- First item
- Second item
  - Nested item
  - Another nested item
- Third item

An ordered list:

1. First step
2. Second step
3. Third step

A task list:

- [x] This item is done
- [ ] This item is still in progress
- [ ] A third item

## Tables

| Technology | Field | Status |
| --- | --- | :---: |
| Laravel | Web development | Stable |
| Flutter | Mobile apps | Stable |
| PyTorch | Machine learning | In progress |

## Description lists

Markdown
: A lightweight plain-text format that renders to HTML.

Front Matter
: A short header at the top of a file that carries the title, date, and tags.

## Code

Inline code looks like this: `php artisan route:list`. Code blocks are highlighted
automatically and get a copy button in the corner:

```php
<?php

use Illuminate\Support\Facades\Route;

Route::get('/blog/{slug}', function (string $slug) {
    return view('blog-post', ['slug' => $slug]);
})->name('blog.show');
```

Other languages work the same way, such as JavaScript:

```javascript
function fibonacci(n) {
    const sequence = [0, 1];

    for (let i = 2; i < n; i += 1) {
        sequence.push(sequence[i - 1] + sequence[i - 2]);
    }

    return sequence;
}
```

Or Python:

```python
def normalize(values):
    """Return the values scaled between zero and one."""
    low, high = min(values), max(values)

    return [(value - low) / (high - low) for value in values]
```

## Images

To add an image, put its description in square brackets and its path in parentheses:

```
![Image description](/images/example.png)
```

## Footnotes

You can add numbered footnotes[^1] at the end of a post, like this one[^2].

[^1]: This is the first footnote. It is rendered separately at the end of the post.

[^2]: The second footnote.

## Wrapping up

Everything above works directly inside `.md` files in the `resources/content/posts`
folder, and is rendered to HTML on demand with automatic caching.
