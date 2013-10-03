<?php
namespace models;

class Guestbook {
    public static $targetpage = '/user/nightwolfz';
	/**
     * Fetch guestbook table from the database
     *
     * @param int $ownerId owner_id to select
	 * @param int $posterId poster_id to select
	 * @param int $limit limit to how many results?
     * @return Object
     */
	static function selectId($ownerId = null, $page = 0, $limit = 5){
		$db = \models\MySQL::getInstance();

        //first item to display on this page
        $start = $page ? (($page - 1) * $limit) : 0;
        $page = $page == 0 ? 1 : 0;
        $results = array();

        // May have problems showing duplicated posts, test this!
        $results = $db->queryAll("SELECT owner_id, poster_id, poster_name, content, `timestamp`, gender FROM guestbook g LEFT JOIN profiles p ON g.poster_id = p.login_id WHERE "
            .($ownerId ? "g.owner_id='".(int)$ownerId."'" : "")
            ." ORDER BY g.timestamp DESC LIMIT $start, $limit"
        );
        /*
        $results = $db->queryAll("SELECT * FROM guestbook WHERE "
            .($ownerId ? "owner_id='".(int)$ownerId."'" : "")
            ." ORDER BY timestamp DESC LIMIT $start, $limit"
        );*/

        foreach($results as $k => $result){
            $results[$k]->thumb = \models\Profiles::getAvatar($result->poster_id, '', true);
        }


		return $results;
	}

    static function count($ownerId, $limit = 100, $table = 'guestbook', $idName = 'owner_id', $countOnColumn = '*'){
        $db = \models\MySQL::getInstance();
        $db->query("SELECT COUNT($countOnColumn) as num FROM $table ".($ownerId ? "WHERE $idName='".(int)$ownerId."'" : "")." LIMIT $limit");
        $count = $db->fetch();
        return $count->num;
    }

    static function createPagination($ownerId, $page, $limit = 5, $targetpage = null, $table = 'guestbook', $countOnColumn = '*', $idName = 'owner_id'){
        $count = self::count($ownerId, 200, $table, $idName, $countOnColumn);
        $limit = $limit ?: 5;
        $lastpage = floor($count/$limit);
        $targetpage = self::$targetpage = $targetpage ?: self::$targetpage;
        $pagination = array();
        $adjacents = 2;

        if ($lastpage > 1) {
            $next = $page + 1;
            $prev = $page - 1;
            //previous button
            $pagination[] = ($page > 1) ? "<a href=\"$targetpage/$prev\">«« newer</a>" : "";

            //pages
            if($lastpage < 7 + ($adjacents * 2))    //not enough pages to bother breaking it up
            {
                for ($counter = 1; $counter <= $lastpage; $counter++){
                    $pagination[] = self::paginationElement($counter, $page);
                }
            }
            else if($lastpage > 5 + ($adjacents * 2)) //enough pages to hide some
            {
                //close to beginning; only hide later pages
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
                        $pagination[] = self::paginationElement($counter, $page);
                    }
                    $pagination[] = "<a>...</a>";
                    $pagination[] = "<a href=\"$targetpage/$lastpage\">$lastpage</a>";
                }
                //in middle; hide some front and some back
                elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination[] = "<a href=\"$targetpage/1\">1</a>";
                    $pagination[] = "<a href=\"$targetpage/2\">2</a>";
                    $pagination[] = "<a>...</a>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        $pagination[] = self::paginationElement($counter, $page);
                    }
                    $pagination[] = "<a>...</a>";
                    $pagination[] = "<a href=\"$targetpage/$lastpage\">$lastpage</a>";
                }
                //close to end; only hide early pages
                else
                {
                    $pagination[] = "<a href=\"$targetpage/1\">1</a>";
                    $pagination[] = "<a href=\"$targetpage/2\">2</a>";
                    $pagination[] = "<a>...</a>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        $pagination[] = self::paginationElement($counter, $page);
                    }
                }
            }

            //next button
            $pagination[] = ($page < $counter - 1) ? "<a href=\"$targetpage/$next\">older »»</a>" : "<span class=\"disabled\">older »»</span>";

            //javascript
            $pagination[] .= "<script>
            $(document).ready(function () {
                $('a.arrowLeft').attr('href', '".($page > 1 ? "$targetpage/$prev" : "")."');
                $('a.arrowRight').attr('href', '".($page < $counter - 1 ? "$targetpage/$next" : "")."');
            });
            </script>";
        }
        return $pagination;
    }

    static function paginationElement($counter, $page){
        return ($counter == $page) ? "<span class=\"active\">$counter</span>" : "<a href=\"".self::$targetpage."/$counter\">$counter</a>";
    }


}

?>