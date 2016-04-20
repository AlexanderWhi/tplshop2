<?

class LibNews {

    private static $instance;

    /**
     * @return LibCatalog
     */
    static function getInstance() {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * @return LibCatalog
     */
    static function getPrototype() {
        return new self;
    }

    /**
     * акции
     * @return type
     */
    static function getActions() {
        return DB::select("SELECT * FROM sc_news WHERE type='action' ORDER BY id DESC")->toArray();
    }

}
