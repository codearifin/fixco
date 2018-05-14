<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ngc_product_review extends Model
{
    //
    protected $table        = 'ngc_product_review';
    protected $guarded      = array();
    public $timestamps      = false;

    public static function getProductReview($id){
        $review = self::where('product_id',$id)->where('publish',1)->orderBy('modified_datetime','desc');

        return $review->paginate(3);
        return $review->get();
    }

    public static function getJumlahReview($id){
        $review = self::where('product_id',$id)->where('publish',1);

        return $review->get()->count();
    }

    //added by HH 2017-01-05 13:52:00

    public static function checkReviewUser($username,$idproduct){

        $check = self::whereRaw("name = '$username' AND product_id = $idproduct")->where('publish',1)->first();

        if(count($check)>0) { 
            return 1;
        }else { return 0; }

    }

    public static function getReviewSpesificProduct($id){
        return self::where('product_id',$id)->where('publish',1)->get();
    }
    
    public static function findRatingByProduct($id){
        $model = self::select(DB::raw('SUM(rating) AS sum_rating, COUNT(id) AS counter_review'))
                ->where(['product_id' => $id, 'publish' => 1])
            ->groupBy('product_id');
        
        return $model->first();
    }
}
