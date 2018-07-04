---
layout: default
permalink: /data_lists/custom_data_list.html
---

# Data list - Create a custom Datalist class

```php
namespace App\Controller;

use App\Datalist\Type\NewsDatalistType;
use App\Repository\NewsRepository;
use Leapt\CoreBundle\Datalist\DatalistFactory;
use Leapt\CoreBundle\Datalist\Datasource\DoctrineORMDatasource;
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
        
        $datalist = $this->datalistFactory
            ->createBuilder(NewsDatalistType::class)
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

```php
namespace App\Datalist\Type;

use App\Entity\News;
use Leapt\CoreBundle\Datalist\Action\Type\SimpleActionType;
use Leapt\CoreBundle\Datalist\DatalistBuilder;
use Leapt\CoreBundle\Datalist\Field\Type\DateTimeFieldType;
use Leapt\CoreBundle\Datalist\Field\Type\TextFieldType;
use Leapt\CoreBundle\Datalist\Filter\Type\SearchFilterType;
use Leapt\CoreBundle\Datalist\Type\DatalistType;

final class NewsDatalistType extends DatalistType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'limit_per_page' => 10,
                'data_class'     => News::class,
            ])
        ;
    }
    
    public function buildDatalist(DatalistBuilder $builder, array $options)
    {
        $builder
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
        ;
    }
}
```

----------

[Go back to Data lists documentation](/data_lists.html)
