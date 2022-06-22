# File Uploads

The `File` attribute is available to automatically handle file uploads for your entities.

!!! example "Usage"

    === "Entity"
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

    === "Form"
        ```php
        use Leapt\CoreBundle\Form\Type\FileType;

        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('file', FileType::class, [
                    'label'        => 'press_radio_spot.field.file',
                    'file_path'    => 'image', // Required, see Options
                    'allow_delete' => true,
                    'file_label'   => 'file_type.label',
                ])
            ;
        }
        ```

!!! info "Options"

    Mandatory options:
    
    * `path`, `pathCallback` or `flysystemConfig`
    * `mappedBy`
    
    | Name | Description |
    | ---- | ----------- |
    | path | Path where to store files. |
    | pathCallback | Callback that returns the path where to store files. |
    | mappedBy | Class property that will be used to store the file path. |
    | nameCallback | Callback that returns a string that will be used to generate the filename. |
    | flysystemConfig | Name of the Flysystem storage to use. |

So there are two options to handle file uploads:

* either specify the `path` or `pathCallback` option to store files locally
* or specify the `flysystemConfig` option, so you can store files anywhere using [Flysystem](https://flysystem.thephpleague.com/docs/).

!!! note

    To retrieve configured Flysystem storages, the bundle checks for any storage configured using [league/flysystem-bundle](https://github.com/thephpleague/flysystem-bundle).

## Flysystem examples

=== "Entity"
    ```php
    use Leapt\CoreBundle\Doctrine\Mapping as LeaptCore;
    
    class News
    {
        #[ORM\Column(type: 'string')]
        private ?string $image = null;

        #[LeaptCore\File(path: 'uploads/news', mappedBy: 'image', flysystemConfig: 'local')]
        private ?UploadedFile $file = null;
    }
    ```

=== "Form"
    ```php
    use Leapt\CoreBundle\Form\Type\FileType;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'label'        => 'press_radio_spot.field.file',
                'file_path'    => 'image', // Required, see Options
                'allow_delete' => true,
                'file_label'   => 'file_type.label',
            ])
        ;
    }
    ```

=== "config/packages/flysystem.yaml"
    ```yaml
    flysystem:
        storages:
            # Example using local storage
            local:
                adapter: 'local'
                options:
                    directory: '%kernel.project_dir%/public'
            # Example using S3 storage
            s3:
                adapter: 'aws'
                # visibility: public # Make the uploaded file publicly accessible in S3
                options:
                    client: 'aws_client_service'
                    bucket: '%env(AWS_BUCKET)%'
            # Example using Async S3 storage
            s3async:
                adapter: 'asyncaws'
                options:
                    client: 'aws_sync_client_service'
                    bucket: '%env(AWS_BUCKET)%'
    ```

=== "config/services.yaml"
    ```yaml
    # If you use S3 adapters, you must configure a service that will be used by Flysystem
    services:
        # S3 config
        aws_client_service:
            class: Aws\S3\S3Client
            arguments:
                -
                    region: "%env(AWS_REGION)%"
                    version: latest
                    credentials:
                        key: "%env(AWS_KEY)%"
                        secret: "%env(AWS_SECRET)%"

        # Async S3 config    
        aws_sync_client_service:
            class: AsyncAws\S3\S3Client
            arguments:
                -
                    accessKeyId: "%env(AWS_KEY)%"
                    accessKeySecret: "%env(AWS_SECRET)%"
                    region: "%env(AWS_REGION)%"
    ```
