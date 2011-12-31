<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 21.01.2015
 * Time: 14:32
 */

namespace Core;

/**
 * Class Articles - основная логика работы со статьями
 *
 * @package Core
 *
 *
 */
class Articles {

    protected $params = array();
    protected $table = 'zoqa_articles';
    protected $table_group = 'zoqa_article_groups';

    function __construct($params = null)
    {
        if(is_array($params)){
            $this->params = array_merge($this->params, $params);
        }
    }

    function __set($param, $value){
        $this->params[$param] = $value;
    }

    function __get($param){
        if(isset($this->params[$param])) return $this->params[$param];

        return null;
    }

	function setParams(array $params)
	{
		$this->params = array_merge($this->params, $params);

		return $this;
	}

	function getHeaders()
	{
		$app_obj = app()->getInstance();

        $db =  app()->getDb();

        $группа = null;

		$where_condition =  " WHERE art.articles_id > 0 "; // WHERE 1 "; //
		$where = " where t.articles_id > 0 ";

		$offset = 0;
		$limit = 5;

		$order_by = "art.dateline";
		$order = "DESC";

		$having = '';

		if( (string)$this->order_by != '' ) $order_by = $this->order_by;
		if( (string)$this->order != '' ) $order = $this->order;
		if( (string)$this->offset != '' ) $offset = $this->offset;
		if( (string)$this->limit != '' ) $limit = $this->limit;
		if( (string)$this->группа != '' ) $группа = $this->группа;

		// Для обычных юзеров скрывать невидимые статьи c пометкой visibility = 0
		$can_edit = false;
		$user = app()->getAuth()->getIdentity();
		if(is_object($user))
			$can_edit = $user->hasPrivilege($module = 'article', $privilege = 'Edit');
		if($can_edit !== true){
			$where_condition .= " AND  art.visibility = 1 ";
			$where .= " AND  t.visibility = 1 ";
		}


        if($группа !== null){
			//$where_condition .= " AND grp.name = '$группа'";
			$having = " HAVING group_name = '$группа' ";
		}

		// [!] запрос не работал из-за JOIN'a
			// В оператор JOIN фильтр по группе не включишь, поэтому результаты могут быть кривые
			// Вопрос: как учесть фильтр по группе? Например, отказаться от JOIN'а
			// $where = " AND grp.name = '$группа'";
		$_запрос = "
			SELECT art.*, grp.name group_name
			FROM {$this->table} art
			LEFT JOIN {$this->table_group} grp ON grp.groups_id = art.groups_id
			JOIN (select t.articles_id FROM {$this->table} t $where  ORDER BY $order_by $order LIMIT $offset,$limit ) as tbl ON tbl.articles_id = art.articles_id 
			$where_condition
            ";


		$запрос = "SELECT art.*, grp.name group_name
			FROM {$this->table} art
			LEFT JOIN {$this->table_group} grp ON grp.groups_id = art.groups_id
			$where_condition
			$having
			ORDER BY $order_by $order
			LIMIT $offset,$limit
			";

		$articles = $db->fetchAll($запрос);


		if($articles === false)
			throw new \Bluz\Application\Exception\ApplicationException("Ресурс не найден",404);

		if(is_array($articles) AND count($articles) == 0)
			throw new \Bluz\Application\Exception\ApplicationException("Нет статей",404);


		if( !is_array($articles) ){ return false; }

		foreach($articles as $article){
                // if( (int)$article['visibility'] !== 1 ) continue;



                if( (string)$article['descriptor'] !== '' ){
                    $descriptor = $article['descriptor'];
                } else {
                    $descriptor =  $article['articles_id'];
                }
                $link = 'блог/статья/' . $descriptor;

                $intro = preg_replace('/[\\\]{1,100}/i','',$article['intro']);
                $intro = htmlspecialchars_decode( $article["intro"] );
                $intro = $app_obj->Filter($article["intro"],1);

                $tmp_articles[$ind]['articles_id'] = $article['articles_id'];
                $tmp_articles[$ind]['descriptor'] = $article['descriptor'];
                $tmp_articles[$ind]['groups_id'] = $article['groups_id'];
                $tmp_articles[$ind]['group_name'] = $article['group_name'];
                $tmp_articles[$ind]['title'] = $article['title'];
                $tmp_articles[$ind]['body'] = substr($article['body'],0,250);
                $tmp_articles[$ind]['dateline'] = $app_obj->Date((int)$article["dateline"]); //date( "Y-m-d H:i:s", $article["dateline"] );
                $tmp_articles[$ind]['visibility'] = $article['visibility'];
                $tmp_articles[$ind]['cover'] = $article['cover'];
                $tmp_articles[$ind]['images_icons'] = $article['images_icons'];
                $tmp_articles[$ind]['link'] = $link;
                $tmp_articles[$ind]['intro'] =  $intro;
                $tmp_articles[$ind]['group_link'] = 'блог/группа/' . $article['group_name'];


                $ind++;
            }

		unset($articles);
		$article = null;
		$headers = $tmp_articles;

		return $headers;



























	}
}