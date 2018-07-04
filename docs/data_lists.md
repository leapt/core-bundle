---
layout: default
permalink: /data_lists.html
field_types: ["Boolean", "DateTime", "Heading", "Image", "Label", "Text", "Url"]
filter_types: ["Choice", "Entity", "Search"]
---

# Data lists

The Datalist component will help you create powerful data lists and lets you:

- Specify a data source (bundle provides datasource handlers for arrays and Doctrine ORM)
- Define all the fields (data) you want
- Create filters to narrow the search
- Paginate automatically (using the [Paginator](/paginator.html) defined earlier)

Summary:

- [Create your first Data list](#first-data-list)
- [Render the Data list](#render)
- [Available Field Types](#field-types)
- [Available Filter Types](#filter-types)
- [Available Action Types](#action-types)

## <a name="first-data-list"></a> Create your first Data list

The following example creates a paginated list of News (10 per page), ordered by descending publication date.

It will display a search filter, two fields (title and publicationDate), and a link to update the news.

```php
namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Leapt\CoreBundle\Datalist\Action\Type\SimpleActionType;
use Leapt\CoreBundle\Datalist\DatalistFactory;
use Leapt\CoreBundle\Datalist\Datasource\DoctrineORMDatasource;
use Leapt\CoreBundle\Datalist\Field\Type\DateTimeFieldType;
use Leapt\CoreBundle\Datalist\Field\Type\TextFieldType;
use Leapt\CoreBundle\Datalist\Filter\Type\SearchFilterType;
use Leapt\CoreBundle\Datalist\Type\DatalistType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class NewsController extends Controller
{
    /**
     * @var DatalistFactory
     */
    private $datalistFactory;
    
    /**
     * @var NewsRepository
     */
    private $newsRepository;
    
    public function __construct(DatalistFactory $datalistFactory, NewsRepository $newsRepository)
    {
        $this->datalistFactory = $datalistFactory;
        $this->newsRepository = $newsRepository;
    }
    
    public function index(Request $request): Response
    {
        $queryBuilder = $this->newsRepository->createQueryBuilder('e')
            ->orderBy('e.publicationDate', 'DESC');
        
        $datalist = $this->datalistFactory->createBuilder(DatalistType::class, [
                'limit_per_page' => 10,
                'data_class'     => News::class,
            ])
            ->addField('title', TextFieldType::class, [
                'label' => 'news.title',
            ])
            ->addField('publicationDate', DateTimeFieldType::class, [
                'label'  => 'news.publication_date',
                'format' => 'Y/m/d',
            ])
            ->addFilter('title', SearchFilterType::class, [
                'label'         => 'news.title',
                'search_fields' => ['e.title'],
            ])
            ->addAction('update', SimpleActionType::class, [
                'route'  => 'app_news_update',
                'label'  => 'content.index.update',
                'params' => ['id' => 'id'],
            ])
            ->getDatalist();

        $datalist->setRoute($request->attributes->get('_route'))
            ->setRouteParams($request->query->all());
        $datasource = new DoctrineORMDatasource($queryBuilder);
        $datalist->setDatasource($datasource);
        $datalist->bind($request);
        
        return $this->render('news/index.html.twig', [
            'datalist' => $datalist,
        ]);
    }
}
```

You can also lighten your controller by [creating a custom Datalist class](/data_lists/custom_data_list.html).

## <a name="render"></a> Render the Data list

```
{{ "{% if datalist is empty " }}%}
    No news available.
{{ "{% else " }}%}
    {{ "{{ datalist_widget(datalist) " }}}}
{{ "{% endif " }}%}
```

The data list is built using the `@LeaptCore/Datalist/datalist_grid_layout.html.twig` by default, but you can
of course create your own. Here are the templates provided by the bundle:

- `@LeaptCore/Datalist/datalist_grid_layout.html.twig` (default)
- `@LeaptCore/Datalist/datalist_tiled_layout.html.twig`

And like the Paginator component, you can override it using a Twig tag:

```twig
{{ "{% datalist_theme datalist '@LeaptCore/Datalist/datalist_grid_layout.html.twig' " }}%}
```

Don't hesitate to create your own to adapt it to your layout/styles.

## <a name="field-types"></a> Available Field Types

Here are the Field Types provided by the bundle. Feel free to check the classes to know the available options.

You can also create your own.

{% for field_type in page.field_types %}
- [{{ field_type }}FieldType](https://github.com/leapt/core-bundle/blob/master/Datalist/Field/Type/{{ field_type }}FieldType.php){% endfor %}

## <a name="filter-types"></a> Available Filter Types

Here are the Filter Types provided by the bundle. Feel free to check the classes to know the available options.

You can also create your own.

{% for filter_type in page.filter_types %}
- [{{ filter_type }}FieldType](https://github.com/leapt/core-bundle/blob/master/Datalist/Filter/Type/{{ filter_type }}FilterType.php){% endfor %}

## <a name="action-types"></a> Available Action Types

There is currently one Action Type provided by the bundle: [SimpleActionType](https://github.com/leapt/core-bundle/blob/master/Datalist/Action/Type/SimpleActionType.php).
Feel free to check the class to know the available options.

You can also create your own.

----------

&larr; [Paginator](/paginator.html)

[RSS Feeds](/rss_feeds.html) &rarr;
