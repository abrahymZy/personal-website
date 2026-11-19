---
title: "How to add a new post to this blog"
date: 2026-11-12
tags: [blog, laravel, markdown]
summary: "A short walkthrough for adding a post in both languages: the file name, the front matter, and how it shows up on the site."
---

Posts here are plain text files, with no database and no admin panel.
Each post is a single `.md` file per language.

## 1. The file name

Files live in `resources/content/posts` and follow this pattern:

```
{key}.{locale}.md
```

So one post in two languages becomes two files:

```
building-a-blog.ar.md
building-a-blog.en.md
```

The key is what appears in the post URL: `/blog/building-a-blog`. It is identical in both
languages, so the URL stays stable when you switch language and only the content changes.

## 2. The front matter

At the top of every file sits a small header between two `---` lines:

```
---
title: "Post title"
date: 2026-11-12
tags: [tag, another-tag]
summary: "One or two lines that appear on the post card and in search results."
---
```

- `title` is the post title, shown on the page and in the browser tab.
- `date` is the publication date, and it drives the ordering of posts.
- `tags` are optional and written inside square brackets.
- `summary` is an optional short description.

## 3. The content

Below the front matter the post is written in full Markdown. See the
[complete Markdown guide](/blog/markdown-guide) for every supported feature.

## 4. Publishing

No build step is required. Add the file, refresh the page, and the post appears.
Files are read on demand, cached for an hour, and the cache is invalidated automatically
the moment any post file changes.
