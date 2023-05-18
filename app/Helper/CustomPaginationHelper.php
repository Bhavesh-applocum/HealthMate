<?php

namespace App\Helpers;

class CustomPaginationHelper
{
    // create custom pagination function named paginate_data()
    public static function paginate_data($query, $page)
    {
        $is_last_page = false; // default last page is false
        $limit = 10; // limit also workd as count per page
        $page = (int)$page; // this will convert page to int from string
        $offset = ($page*$limit)-$limit; 
        $lastPage = ceil(count($query->get())/$limit); // divided total by limitto get last page
        if($page == $lastPage){ // is page and last_page is equal then make is_last_page true
            $is_last_page = true;
        }
        if($is_last_page == true){
            $lastPage = 0;
        }
        $query = $query->skip($offset)->take($limit)->orderBy('id', 'desc')->get();
        $paginatedData = [];
        $paginatedData['data'] = $query;
        $paginatedData['is_last_page'] = $is_last_page;
        // dd($paginatedData['is_last_page']);
        $paginatedData['last_page'] = $lastPage;
        $paginatedData['current_page'] = $page;
        // dd($paginatedData);
        return $paginatedData;
    }

    // create custom pagination function named paginate_data() for dashboard

    public static function mainPage_data($query, $page)
    {
        $is_last_page = false; // default last page is false
        $limit = 5; // limit also workd as count per page
        $page = (int)$page; // this will convert page to int from string
        $offset = ($page*$limit)-$limit;
        // dd($offset); 
        $lastPage = ceil($query->count()/$limit); // divided total by limitto get last page
        if($page == $lastPage){ // is page and last_page is equal then make is_last_page true
            $is_last_page = true;
        }
        if($is_last_page == true){
            $lastPage = 0;
        }
        $query = $query->skip($offset)->take($limit)->orderBy('id', 'desc')->get();
        // dd($query);
        $paginatedData = [];
        $paginatedData['data'] = $query;
        $paginatedData['is_last_page'] = $is_last_page;
        $paginatedData['last_page'] = $lastPage;
        $paginatedData['current_page'] = $page;
        return $paginatedData;
    }


}