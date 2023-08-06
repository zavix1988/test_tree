<?php

namespace Core\Base;

class View
{
    public $route = [];

    public $view;

    public $layout;

    public $scripts = [];

    public static $meta = [
        'title' => '',
        'desc' => '',
        'keywords' => ''
    ];

    public function __construct($route, $layout = '', $view='')
    {
        $this->route = $route;
        if ($layout === false){
            $this->layout = false;
        } else {
            $this->layout = $layout ?: LAYOUT;
        }
        $this->view = $view;
    }


    /**
     * Render view
     *
     * @param $data
     * @return void
     * @throws \Exception
     */
    public function render($data)
    {
        if(is_array($data))extract($data);
        $this->route['prefix'] = str_replace('\\', '/', $this->route['prefix']);
        $fileView = APP . "/Views/{$this->route['prefix']}{$this->route['controller']}/{$this->view}.php";
        ob_start();
        if (is_file($fileView)){
            require $fileView;
        } else {
            throw new \Exception("<p>View <b>{$fileView}</b> not found </p>", 404);
        }
        $content = ob_get_clean();

        if (false !== $this->layout){
            $fileLayout = APP . "/Views/layouts/{$this->layout}.php";
            if(is_file($fileLayout)){
                $content = $this->getScript($content);
                if (!empty($this->scripts)){
                    $scripts = $this->scripts[0];
                }
                require $fileLayout;
            } else {
                echo "<p>Template <b>$fileLayout</b> not found</p>";
            }
        }

    }

    /**
     * Getting scripts.
     * @param $content
     * @return array|mixed|string|string[]|null
     */
    protected function getScript($content)
    {
        $pattern = "#<script.*?>.*?</script>#si";
        preg_match_all($pattern, $content, $this->scripts);
        if(!empty($this->scripts)){
            $content = preg_replace($pattern, '', $content);
        }
        return $content;
    }

    /**
     * @return void
     */
    public static function getMeta()
    {
        echo '<title>' . self::$meta['title']. '</title>
        <meta name="description" content="'. self::$meta['desc']. '">
        <meta name="keywords" content="' . self::$meta['keywords'] . '">';
    }

    /**
     * @param $title
     * @param $desc
     * @param $keywords
     * @return void
     */
    public static function setMeta($title = '', $desc = '', $keywords = '')
    {
        self::$meta['title'] = $title;
        self::$meta['desc'] = $desc;
        self::$meta['keywords'] = $keywords;
    }
}