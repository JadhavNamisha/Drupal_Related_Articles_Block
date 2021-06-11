<?php
namespace Drupal\related_articles;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Url;

class ArticleData
{
    protected $entityTypeManager;
    public function __construct(EntityTypeManager $entity_type_manager)
    {
        $this->entityTypeManager = $entity_type_manager;
    }
    public function fetchArticle_sameCategory()
    {
		$node = \Drupal::routeMatch()->getParameter('node');
		$current_node_title = $node->getTitle();
		$current_node_type = $node->getType();
		$current_node_author = $node->getOwner()->id();;
		\Drupal::logger('article_custom')->notice($current_node_author);
		if($current_node_type == 'article')
		{
			$termId = $node->get('field_category')->target_id;
			$nids = \Drupal::entityQuery('node')
				->condition('type','article')
				->condition('status', 1, '=')
				->condition('field_category.entity.tid', $termId)
				->sort('title', 'ASC')
				->sort('created' , 'DESC')
				->groupBy('uid')
				->execute();
			
			$nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);
			$article_titles = array();
			foreach ($nodes as $node)
			{	
				if( $node->getTitle() != $current_node_title ){
					$nid = $node->id();
					$url = Url::fromRoute('entity.node.canonical', ['node' => $nid])->toString();
					$article_titles[$url] = $node->getTitle();
				}
			}
			return $article_titles;
		}
		return 0;
    }
	public function fetchArticle_differentCategory()
    {
		$node = \Drupal::routeMatch()->getParameter('node');
		$current_node_title = $node->getTitle();
		$current_node_type = $node->getType();
		if($current_node_type == 'article')
		{
			$termId = $node->get('field_category')->target_id;
			$nids = \Drupal::entityQuery('node')
				->condition('type','article')
				->condition('status', 1, '=')
				->condition('field_category.entity.tid', $termId, '!=')
				->sort('title', 'ASC')
				->sort('created' , 'DESC')
				->groupBy('uid')
				->execute();
			
			$nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);
			$article_titles = array();
			foreach ($nodes as $node)
			{	
				if( $node->getTitle() != $current_node_title ){
					$nid = $node->id();
					$url = Url::fromRoute('entity.node.canonical', ['node' => $nid])->toString();
					$article_titles[$url] = $node->getTitle();
				}
			}
			return $article_titles;
		}
		return 0;
    }
}
