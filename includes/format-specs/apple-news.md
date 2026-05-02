# Apple News Format (ANF) Specification

## Overview
Transform content into Apple News Format JSON (version 1.11). The output must be a valid JSON object conforming to Apple's ANF specification.

## Top-Level Structure

```json
{
  "version": "1.11",
  "identifier": "post-{post_id}",
  "language": "en",
  "title": "Article Title",
  "layout": {
    "columns": 15,
    "width": 1024,
    "margin": 100,
    "gutter": 20
  },
  "documentStyle": {
    "backgroundColor": "#FFFFFF"
  },
  "components": [],
  "componentTextStyles": {},
  "componentLayouts": {},
  "metadata": {
    "excerpt": "Article excerpt or summary"
  }
}
```

## Component Roles

Each component is a JSON object with at minimum a `role` and content fields. Common roles:

### Text Components
- `body`: Main article text. Use `format: "html"` for rich text.
- `heading1` through `heading6`: Section headings.
- `intro`: Sub-headline or deck text, placed after the title.
- `byline`: Author attribution line.
- `quote`: Block quotes and pull quotes.
- `caption`: Image or figure captions.

### Media Components
- `photo`: Images. Requires `URL` field with the image URL.
- `embedwebvideo`: Embedded videos. Requires `URL` field.

### Container Components
- `container`: Groups nested `components` together. Used for callout boxes, collapsible sections, etc.

### Interactive Components
- `link_button`: Call-to-action buttons. Requires `text`, `URL`, and can reference named styles.

## Component Structure Examples

### Body Text
```json
{
  "role": "body",
  "text": "<p>72% of Americans say they trust local news.</p>",
  "format": "html",
  "textStyle": "default-body",
  "layout": "body-layout"
}
```

### Heading
```json
{
  "role": "heading2",
  "text": "Key Findings",
  "format": "html",
  "textStyle": "default-heading-2",
  "layout": "body-layout"
}
```

### Image
```json
{
  "role": "photo",
  "URL": "https://example.com/image.jpg",
  "layout": "full-width-image",
  "caption": "Image description"
}
```

### Block Quote
```json
{
  "role": "quote",
  "text": "<p>Quoted text here.</p>",
  "format": "html",
  "textStyle": "default-blockquote-left",
  "layout": "blockquote-layout"
}
```

### Callout / Info Box
```json
{
  "role": "container",
  "layout": "callout-layout-full",
  "style": {
    "backgroundColor": "#f7f7f1",
    "border": {
      "all": { "width": 1, "style": "solid", "color": "#dededf" },
      "bottom": true, "right": true, "top": true, "left": true
    }
  },
  "components": [
    {
      "role": "body",
      "text": "<p>Callout content here.</p>",
      "format": "html"
    }
  ]
}
```

## Named Styles (componentTextStyles)

Define these standard text styles in `componentTextStyles`:

```json
{
  "default-body": {
    "fontName": "IowanOldStyle",
    "fontSize": 18,
    "lineHeight": 28,
    "textColor": "#333333"
  },
  "default-heading-2": {
    "fontName": "HelveticaNeue-Bold",
    "fontSize": 28,
    "lineHeight": 34,
    "textColor": "#000000"
  },
  "default-blockquote-left": {
    "fontName": "IowanOldStyle-Italic",
    "fontSize": 20,
    "lineHeight": 30,
    "textColor": "#555555"
  },
  "default-byline": {
    "fontName": "HelveticaNeue",
    "fontSize": 14,
    "lineHeight": 20,
    "textColor": "#666666"
  }
}
```

## Named Layouts (componentLayouts)

```json
{
  "body-layout": {
    "columnStart": 0,
    "columnSpan": 15,
    "margin": { "top": 12, "bottom": 12 }
  },
  "full-width-image": {
    "columnStart": 0,
    "columnSpan": 15,
    "margin": { "top": 20, "bottom": 20 }
  },
  "blockquote-layout": {
    "columnStart": 2,
    "columnSpan": 11,
    "margin": { "top": 16, "bottom": 16 }
  },
  "callout-layout-full": {
    "columnStart": 0,
    "columnSpan": 15,
    "margin": { "top": 20, "bottom": 20 },
    "padding": { "top": 16, "bottom": 16, "left": 16, "right": 16 }
  }
}
```

## Conversion Rules

1. **Paragraphs** become `body` components with `format: "html"`. Adjacent paragraphs can be merged into a single component.
2. **Headings** (h1-h6) become the corresponding `heading1`-`heading6` role.
3. **Images** become `photo` components. Use the full-resolution image URL.
4. **Block quotes** become `quote` components.
5. **Lists** (ordered and unordered) should be rendered as HTML within a `body` component.
6. **Tables** should be rendered as HTML within a `body` component.
7. **Embedded videos** (YouTube, Vimeo) become `embedwebvideo` components.
8. **Charts and data visualizations** should be described as text in a `body` component or rendered as an image if a static URL is available.
9. **Horizontal rules** can be omitted or rendered as a `divider` component.

## Content Fidelity

- ALL factual content, statistics, data points, and citations must be preserved exactly.
- Do not summarize, paraphrase, or omit any content.
- Preserve the original reading order.
- Image URLs must be preserved exactly as provided.

## Output Requirements

- Output ONLY the JSON object. No wrapping, no markdown code fences, no explanation.
- The JSON must be valid and parseable.
- Every component must have a `role` field.
- Text components using rich formatting must set `format: "html"`.
