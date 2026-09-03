# Data lists: Url field

Behaves like the [Text field](text_field.md) (it actually extends `TextFieldType`), but wraps the displayed value in
an `<a>` link.

## Example

!!! example ""

    ```php
    $builder
        // Link points to the field's own value
        ->addField('website', UrlFieldType::class)
        // Link points to a custom URL computed from the row
        ->addField('article', UrlFieldType::class, [
            'url' => fn(Comment $comment): string => $this->urlGenerator->generate('blog_view', ['slug' => $comment->getArticle()->getSlug()]),
        ])
        ->getDatalist();
    ```

## Options

!!! info ""

    | Option | Default | Description |
    | --- | --- | --- |
    | `url` | not set | The link's `href`. Can be a string, or a `callable` receiving the current row and returning a string. When not set, the field's own value is used as the `href`. |

    This field type also supports the [common field options](common_options.md). Note that although it inherits the
    `truncate` option and the `escape` behavior from `TextFieldType`, the bundle's built-in themes don't apply either
    of them to the link text.

## Block name

The block rendered for this field type is `url_field`. Override it in your own Datalist theme if you need to
customize the markup.

[Go back to Data lists documentation](../../data_lists.md)
