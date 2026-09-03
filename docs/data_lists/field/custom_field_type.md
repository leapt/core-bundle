# Data lists: Create a custom field type

When none of the [built-in field types](../../data_lists.md#available-field-types) fit your needs, you can create your
own by extending `AbstractFieldType`.

## Create the field type

!!! example ""

    ```php
    namespace App\Datalist\Field\Type;

    use Leapt\CoreBundle\Datalist\Field\DatalistFieldInterface;
    use Leapt\CoreBundle\Datalist\Field\Type\AbstractFieldType;
    use Leapt\CoreBundle\Datalist\ViewContext;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class RatingFieldType extends AbstractFieldType
    {
        public function configureOptions(OptionsResolver $resolver): void
        {
            parent::configureOptions($resolver);

            $resolver->setDefaults([
                'max' => 5,
            ]);
            $resolver->setAllowedTypes('max', 'int');
        }

        public function buildViewContext(ViewContext $viewContext, DatalistFieldInterface $field, mixed $value, array $options): void
        {
            parent::buildViewContext($viewContext, $field, $value, $options);

            $viewContext['max'] = $options['max'];
        }

        public function getName(): string
        {
            return 'rating';
        }

        public function getBlockName(): string
        {
            return 'rating';
        }
    }
    ```

- `configureOptions()` declares the options your field type accepts, on top of the [common field options](common_options.md)
  it already inherits from `AbstractFieldType`. It follows the same
  [`OptionsResolver`](https://symfony.com/doc/current/components/options_resolver.html) API used by Symfony forms.
- `buildViewContext()` populates the variables made available to the Twig block. Always call `parent::buildViewContext()`
  first: it resolves `value` (through `property_path` or `callback`), and exposes `field`, `options` and
  `translation_domain`. Note that the `$value` argument it receives is the **current row**, not the field's own
  resolved value — that's why `parent::buildViewContext()` needs it to compute `value` via `property_path`.
- `getBlockName()` is used to resolve which Twig block renders the field (see below).
- `getName()` is required by the interface but isn't used to render fields; it just needs to return a unique string.

You don't need to register the field type anywhere: any service implementing `FieldTypeInterface` is automatically
tagged and picked up by the bundle, as long as your app has `autoconfigure: true` (the Symfony default).

## Render it in a Datalist theme

The block rendered for a field is named after `getBlockName()`, suffixed with `_field` — here, `rating_field`. Add it
to your own Datalist theme (see [Render the Data list](../../data_lists.md#render-the-data-list)):

!!! example ""

    ```twig
    {% extends '@LeaptCore/Datalist/datalist_grid_layout.html.twig' %}

    {% block rating_field %}
        <td>
            {% if value is not null %}
                {% for i in 1..value %}⭐{% endfor %} <span class="text-muted">/ {{ max }}</span>
            {% else %}
                <span class="empty-value">{{ 'datalist.empty_value'|trans({}, 'LeaptCoreBundle') }}</span>
            {% endif %}
        </td>
    {% endblock rating_field %}
    ```

All the variables set on `$viewContext` in `buildViewContext()` (`value`, `options`, `max`, ...) are available in the
block.

!!! info "Overriding a single field instance"

    If you need a different rendering for one specific field of one specific Datalist type, you can instead define a
    block named `_<datalist type's block name>_<field name>_field` — e.g. `_datalist_rating_field` for a field named
    `rating` on a Datalist type whose `getBlockName()` returns the default `datalist` — which takes precedence over
    `rating_field` for that field only.

## Use it

!!! example ""

    ```php
    $builder
        ->addField('rating', RatingFieldType::class, [
            'max' => 10,
        ])
        ->getDatalist();
    ```

[Go back to Data lists documentation](../../data_lists.md)
