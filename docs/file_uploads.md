---
layout: default
permalink: /file_uploads.html
---

# File Uploads

The `File` annotation/attribute is available to automatically handle file uploads for your entities.

```php
use Leapt\CoreBundle\Doctrine\Mapping as LeaptCore;

class News
{
    /**
     * @ORM\Column(type="string")
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string")
     */
    private ?string $image = null;

    // Available as an annotation
    /**
     * @LeaptCore\File(path="uploads/news", mappedBy="image", nameCallback="name")
     */
    // Or as a PHP 8 attribute
    #[LeaptCore\File(path: 'uploads/news', mappedBy: 'image', nameCallback: 'name')]
    private ?UploadedFile $file = null;
}
```

### Options

Mandatory options:

* `path` or `pathCallback`
* `mappedBy`

| Name | Description |
| ---- | ----------- |
| path | Path where to store files. |
| pathCallback | Callback that returns the path where to store files. |
| mappedBy | Class property that will be used to store the file path. |
| nameCallback | Callback that returns a string that will be used to generate the filename. |

----------

&larr; [Form Types](/form_types.html)

[Paginator](/paginator.html) &rarr;
