# Configuration Reference

```yaml
# config/packages/leapt_core.yaml
leapt_core:
    upload_dir: '%kernel.project_dir%/public'
    google_analytics:
        tracking_id: null
        domain_name: auto
        allow_linker: false
        debug: false
    google_tags_manager:
        id: null
    facebook:
        app_id: null
    paginator:
        template: '@LeaptCore/Paginator/paginator_default_layout.html.twig'
    recaptcha:
        public_key:  'your_public_key'
        private_key: 'your_private_key'
        enabled: true
        verify_host: false
        locale_key: '%kernel.default_locale'
        locale_from_request: false
        ajax: false
        api_host: www.google.com
        hide_badge: false
        score_threshold: 0.5
        http_proxy:
            host: null
            port: null
            auth: null
```
