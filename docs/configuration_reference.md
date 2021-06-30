# Configuration Reference

```yaml
# config/packages/leapt_core.yaml
leapt_core:
    upload_dir: '%kernel.project_dir%/public'
    paginator:
        template: '@LeaptCore/Paginator/paginator_default_layout.html.twig'
    recaptcha:
        public_key:  'your_public_key'
        private_key: 'your_private_key'
        enabled: true
        verify_host: false
        ajax: false
        api_host: www.google.com
        hide_badge: false
        score_threshold: 0.5
```
