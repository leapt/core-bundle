# File Uploads

The `File` attribute is available to automatically handle file uploads for your entities.

!!! example "Usage"

    ```php
    use Leapt\CoreBundle\Doctrine\Mapping as LeaptCore;
    
    class News
    {
        #[ORM\Column(type: 'string')]
        private ?string $name = null;
    
        #[ORM\Column(type: 'string')]
        private ?string $image = null;

        #[LeaptCore\File(path: 'uploads/news', mappedBy: 'image', nameCallback: 'name')]
        private ?UploadedFile $file = null;
    }
    ```

!!! info "Options"

    Mandatory options:
    
    * `path` or `pathCallback`
    * `mappedBy`
    
    | Name | Description |
    | ---- | ----------- |
    | path | Path where to store files. |
    | pathCallback | Callback that returns the path where to store files. |
    | mappedBy | Class property that will be used to store the file path. |
    | nameCallback | Callback that returns a string that will be used to generate the filename. |
