<?php

/**
 * database connection Initialization
 * @param $host
 * @param $username
 * @param $password
 * @param $dbName
 * @return bool|resource
 */
function mysqlInit($host, $username, $password, $dbName)
{
    $con = mysqli_connect($host,$username,$password,$dbName);
    if(!$con)
    {
        return false;
    }
    //mysqli_select_db($dbName);
    //set charset
    mysqli_set_charset($con,'utf8');
    return $con;
}
/**
 * encode password
 * @param $password
 * @return bool|string
 */
function createPassword($password)
{
    if(!$password)
    {
        return false;
    }

    return md5(md5($password) . 'headland');
}

/**
 * 消息提示
 * @param int $type 1:success 2:fail
 * @param null $msg
 * @param null $url
 */
function msg($type, $msg = null, $url = null)
{
    //check if the url is empty
    $toUrl = "Location:msg.php?type={$type}";
    $toUrl .= $msg ? "&msg={$msg}" : '';
    $toUrl .= $url ? "&url={$url}" : '';
    header($toUrl);
    exit;
}

/**
 * pic upload
 * @param $file
 * @return string
 */
function imgUpload($file)
{
    //check if the upload film is
    if(!is_uploaded_file($file['tmp_name']))
    {
        msg(2, 'please vis http post to upload pic');
    }
    //to check the film type
    $type = $file['type'];
    if(!in_array($type, array("image/png", "image/gif", "image/jpeg")))
    {
        msg(2, 'the film type must be in png,gif or jpg');
    }
    //upload path
    $uploadPath = './static/file/';
    //upload url
    $uploadUrl = '/static/file/';
    //upload dir
    $fileDir = date('Y/md/', $_SERVER['REQUEST_TIME']);

    //check the upload dir is ex
    if(!is_dir($uploadPath . $fileDir))
    {
        mkdir($uploadPath . $fileDir, 0777, true);//recursive to create dir
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    //upload film name
    $img = uniqid() . mt_rand(1000, 9999) . '.' . $ext;
    //upload film full name
    $imgPath = $uploadPath . $fileDir . $img;
    //url
    $imgUrl = '.' . $uploadUrl . $fileDir . $img;
    if(!move_uploaded_file($file['tmp_name'], $imgPath))
    {
        msg(2, 'service is busy, please try later');
    }

    return $imgUrl;


}

/**
 * check if the user is login
 *
 */
function checkLogin()
{
    //open session
    session_start();
    //user not login
    if(!isset($_SESSION['user']) || empty($_SESSION['user']))
    {
        return false;
    }
    return true;

}

/**
 * get current url
 * @return string
 */
function getUrl()
{
    $url = '';
    $url .= $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
    $url .= $_SERVER['HTTP_HOST'];
    $url .= $_SERVER['REQUEST_URI'];
    return $url;
}
/**
 *
 * @param $page
 * @param string $url
 * @return string
 */
function pageUrl($page, $url = '')
{
    $url = empty($url) ? getUrl() : $url;
    //to check if the url exist
    $pos = strpos($url, '?');
    if($pos === false)
    {
        $url .= '?page=' . $page;
    }
    else
    {
        $queryString = substr($url, $pos + 1);
        parse_str($queryString, $queryArr);
        if(isset($queryArr['page']))
        {
            unset($queryArr['page']);
        }
        $queryArr['page'] = $page;
        $queryStr = http_build_query($queryArr);

        $url = substr($url, 0, $pos) . '?' . $queryStr;

    }
    return $url;
}
/**
 * Paging Display
 * @param  int $total total date
 * @param  int $currentPage present page
 * @param  int $pageSize the num per page
 * @param  int $show show number
 * @return string
 */
function pages($total, $currentPage, $pageSize, $show = 6)
{
    $pageStr = '';

    //if total bigger than rows of page,then paging display
    if($total > $pageSize)
    {
        //total pages
        $totalPage = ceil($total / $pageSize);//get the total pages
        $currentPage = $currentPage > $totalPage ? $totalPage : $currentPage;
        $from = max(1, ($currentPage - intval($show / 2)));
        $to = $from + $show - 1;
        $pageStr .= '<div class="page-nav">';
        $pageStr .= '<ul>';

        //when the pages more than one,then show the first page and last page
        if($currentPage > 1)
        {
            $pageStr .= "<li><a href='" . pageUrl(1) . "'>first page</a></li>";
            $pageStr .= "<li><a href='" . pageUrl($currentPage - 1) . "'>last page</a></li>";
        }
        if($to > $totalPage)
        {
            $to = $totalPage;
            $from = max(1, $to - $show + 1);
        }
        if($from > 1)
        {
            $pageStr .= '<li>...</li>';
        }
        for($i = $from; $i <= $to; $i++)
        {
            if($i != $currentPage)
            {
                $pageStr .= "<li><a href='" . pageUrl($i) . "'>{$i}</a></li>";
            }
            else
            {
                $pageStr .= "<li><span class='curr-page'>{$i}</span></li>";
            }
        }
        if($to < $totalPage)
        {
            $pageStr .= '<li>...</li>';

        }
        if($currentPage < $totalPage)
        {
            $pageStr .= "<li><a href='" . pageUrl($currentPage + 1) . "'>下一页</a></li>";
            $pageStr .= "<li><a href='" . pageUrl($totalPage) . "'>尾页</a></li>";
        }
        $pageStr .= '</ul>';
        $pageStr .= '</div>';
    }
    return $pageStr;

}