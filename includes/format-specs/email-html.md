# Email HTML Format Specification

## Overview
Transform content into email-safe HTML compatible with Mailchimp templates and major email clients (Gmail, Outlook, Apple Mail, Yahoo Mail).

## Structural Rules

1. **Table-based layout.** Use `<table>` elements for layout, not CSS flexbox/grid. Email clients have inconsistent CSS support.
2. **Inline CSS only.** All styles must be inline via `style=""` attributes. No `<style>` blocks, no external stylesheets.
3. **Maximum width: 600px.** The outer container table should be 600px wide, centered.
4. **UTF-8 encoding.** Content must be UTF-8 compatible.

## HTML Structure

```html
<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" style="max-width:600px;width:100%;margin:0 auto;font-family:Georgia,'Times New Roman',Times,serif;">
  <tr>
    <td style="padding:20px;">
      <!-- Content sections go here -->
    </td>
  </tr>
</table>
```

## Content Mapping

### Headings
- `h1`: `<h1 style="font-family:Helvetica,Arial,sans-serif;font-size:28px;line-height:34px;color:#000000;margin:0 0 16px 0;padding:0;">Title</h1>`
- `h2`: `<h2 style="font-family:Helvetica,Arial,sans-serif;font-size:22px;line-height:28px;color:#000000;margin:24px 0 12px 0;padding:0;">Heading</h2>`
- `h3`: `<h3 style="font-family:Helvetica,Arial,sans-serif;font-size:18px;line-height:24px;color:#333333;margin:20px 0 8px 0;padding:0;">Heading</h3>`

### Paragraphs
```html
<p style="font-family:Georgia,'Times New Roman',Times,serif;font-size:16px;line-height:26px;color:#333333;margin:0 0 16px 0;">
  Paragraph text here.
</p>
```

### Links
```html
<a href="URL" style="color:#2b6dad;text-decoration:underline;">Link text</a>
```

### Images
```html
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td style="padding:16px 0;">
      <img src="IMAGE_URL" alt="Alt text" width="560" style="display:block;max-width:100%;height:auto;border:0;" />
    </td>
  </tr>
</table>
```

### Block Quotes
```html
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td style="padding:16px 0 16px 20px;border-left:4px solid #d1d1d1;">
      <p style="font-family:Georgia,'Times New Roman',Times,serif;font-size:16px;line-height:26px;color:#555555;font-style:italic;margin:0;">
        Quoted text here.
      </p>
    </td>
  </tr>
</table>
```

### Unordered Lists
```html
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td style="padding:0 0 8px 20px;font-family:Georgia,'Times New Roman',Times,serif;font-size:16px;line-height:26px;color:#333333;">
      &#8226; List item text
    </td>
  </tr>
</table>
```

### Ordered Lists
Use numbered text (1., 2., 3.) with the same table structure as unordered lists.

### Tables (Data)
```html
<table width="100%" cellpadding="8" cellspacing="0" border="0" style="border-collapse:collapse;margin:16px 0;">
  <tr style="background-color:#f2f2f2;">
    <th style="font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:bold;color:#333333;text-align:left;border-bottom:2px solid #d1d1d1;padding:8px;">Header</th>
  </tr>
  <tr>
    <td style="font-family:Georgia,'Times New Roman',Times,serif;font-size:14px;color:#333333;border-bottom:1px solid #eeeeee;padding:8px;">Data</td>
  </tr>
</table>
```

### Horizontal Rule
```html
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td style="padding:20px 0;">
      <hr style="border:0;border-top:1px solid #d1d1d1;margin:0;" />
    </td>
  </tr>
</table>
```

## Email Client Compatibility

- Do NOT use: `<div>`, CSS `float`, `position`, `flexbox`, `grid`, `background-image` on non-table elements, `margin: auto` on non-table elements.
- DO use: `<table>`, `<tr>`, `<td>`, inline `style`, `align`, `width`, `cellpadding`, `cellspacing`, `border` attributes.
- For Outlook: always include `width` attributes on tables and images.
- For Gmail: keep CSS simple; avoid shorthand properties.

## Content Fidelity

- ALL factual content, statistics, data points, and citations must be preserved exactly.
- Do not summarize, paraphrase, or omit any content.
- Preserve the original reading order.
- All links must be preserved with their original URLs.
- Image URLs must be preserved exactly as provided.

## Output Requirements

- Output ONLY the HTML content (the inner table structure). Do NOT include `<!DOCTYPE>`, `<html>`, `<head>`, or `<body>` tags.
- The output should be the content portion that would be inserted into a Mailchimp template's editable region.
- All styles must be inline.
