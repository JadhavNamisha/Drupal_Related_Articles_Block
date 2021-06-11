<?php
/**
 * @file
 * Contains \Drupal\related_articles\Plugin\Block\ArticleDataBlock.
 */
namespace Drupal\related_articles\Plugin\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'related_articles' block.
 *
 * @Block(
 *   id = "article_data_block",
 *   admin_label = @Translation("Relative Articles"),
 *   category = @Translation("Relative Articles")
 * )
 */
class ArticleDataBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
	/* Same Category */
	$article_data = array();
	$count = 0;
	$article_data = \Drupal::service('related_articles.article_data')->fetchArticle_sameCategory();
	echo "<h3>Related Articles:</h3> <br>";
	if( $article_data != 0 ){
		foreach($article_data as $key => $value){
			if($count < 5){
				echo "<a href='".$key."'>".$value."</a><br>";
				$count = $count + 1;
			}
		}
	}
	/* Different Category */
	if( $count < 5 ){
		$article_data = \Drupal::service('related_articles.article_data')->fetchArticle_differentCategory();
		if( $article_data != 0 ){
			foreach($article_data as $key => $value){
				if($count < 5){
					echo "<a href='".$key."'>".$value."</a><br>";
					$count = $count + 1;
				}
			}
			return $article_data;
		}
	}
  }
}
