<?php

namespace api\modules\v1\components;

/**
 * Class Pagination
 * @package api\modules\v1\components
 */
class Pagination extends \yii\data\Pagination
{
    /**
     * @var string name of the parameter storing the current page index.
     * @see params
     */
    public $pageParam = 'offset';
    /**
     * @var string name of the parameter storing the page size.
     * @see params
     */
    public $pageSizeParam = 'limit';
    /**
     * @var bool whether to check if [[page]] is within valid range.
     * When this property is true, the value of [[page]] will always be between 0 and ([[pageCount]]-1).
     * Because [[pageCount]] relies on the correct value of [[totalCount]] which may not be available
     * in some cases (e.g. MongoDB), you may want to set this property to be false to disable the page
     * number validation. By doing so, [[page]] will return the value indexed by [[pageParam]] in [[params]].
     */
    public $validatePage = true;
    /**
     * @var int the default page size. This property will be returned by [[pageSize]] when page size
     * cannot be determined by [[pageSizeParam]] from [[params]].
     */
    public $defaultPageSize = 50;
    /**
     * @var array|false the page size limits. The first array element stands for the minimal page size, and the second
     * the maximal page size. If this is false, it means [[pageSize]] should always return the value of [[defaultPageSize]].
     */
    public $pageSizeLimit = [0, 100];

    private $_page;

    /**
     * Returns the zero-based current page number.
     * @param bool $recalculate whether to recalculate the current page based on the page size and item count.
     * @return int the zero-based current page number.
     */
    public function getPage($recalculate = false)
    {
        if ($this->_page === null || $recalculate) {
            $page = (int)$this->getQueryParam($this->pageParam, 0);
            $this->setPage($page, true);
        }
        return $this->_page;
    }

    /**
     * Sets the current page number.
     * @param int $value the zero-based index of the current page.
     * @param bool $validatePage whether to validate the page number. Note that in order
     * to validate the page number, both [[validatePage]] and this parameter must be true.
     */
    public function setPage($value, $validatePage = false)
    {
        if ($value === null) {
            $this->_page = null;
        } else {
            $value = (int)$value;
            if ($validatePage && $this->validatePage) {
                $pageCount = $this->getPageCount();
                if ($value >= $pageCount) {
                    $value = $pageCount - 1;
                }
            }
            if ($value < 0) {
                $value = 0;
            }
            $this->_page = $value;
        }
    }

    /**
     * @return int number of pages
     */
    public function getPageCount()
    {
        $pageSize = $this->getPageSize();
        if ($pageSize < 1) {
            return 0;
        }
        $totalCount = $this->totalCount < 0 ? 0 : (int)$this->totalCount;
        return (int)($totalCount - $pageSize + 1);
    }

    /**
     * @return int the offset of the data. This may be used to set the
     * OFFSET value for a SQL statement for fetching the current page of data.
     */
    public function getOffset()
    {
        $pageSize = $this->getPageSize();
        return $pageSize < 1 ? 0 : $this->getPage();
    }

    /**
     * @return int the limit of the data. This may be used to set the
     * LIMIT value for a SQL statement for fetching the current page of data.
     * Note that if the page size is infinite, a value -1 will be returned.
     */
    public function getLimit()
    {
        $pageSize = $this->getPageSize();
        return $pageSize < 0 ? -1 : $pageSize;
    }
}