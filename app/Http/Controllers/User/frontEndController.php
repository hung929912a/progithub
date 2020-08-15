<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ToursModel;
use App\Model\guider;
use App\Model\package;
use App\Model\destModel;
use App\Model\banner;
use App\Model\slider;
use App\Model\sliderCustomer;
use App\Model\traveltype_tb;
use App\Model\review;
use DB;
use Auth;

class frontEndController extends Controller
{
    public function getHome(){
        $data['guider'] = guider::where('status',1)->get();
        $data['dest']   = destModel::all();
        $data['tour'] = ToursModel::where('Tours_tb.status',1)->join('traveltype_tb','Tours_tb.tour_id','=','traveltype_tb.tour_id')->orderBy('Tours_tb.tour_id','desc')->get();
        $data['slider'] = slider::where('slider_status',1)->get();
        $data['banner'] = banner::where('banner_id',1)->first('banner_img');
        $data['sliCus'] = sliderCustomer::where('slider_status',1)->take(3)->get();
        return view('frontEnd.index',$data);
    }

    public function getTourDetail($id){
        $tour = ToursModel::find($id);
        $id = $tour->dest_id;
        $dest = destModel::where('dest_id',$id)->first();
        if($tour->package!=null){
            $key = package::wherein('pac_id',json_decode($tour->package))->get();
        }
        $unkey = package::where('status',1)->orderBy('pac_id','desc')->take(4)->get();
        $data = banner::where('banner_id',2)->first('banner_img');
        return view('frontEnd.tour-details',compact('tour','key','unkey','dest','data'));
    }

    public function postReview(request $req){
        $review = new review;
        $review->services = $req->star;
        $review->hospitality = $req->star_1;
        $review->cleanliness = $req->star_2;
        $review->rooms = $req->star_3;
        $review->comfort = $req->star_4;
        $review->satisfaction = $req->star_5;
        $review->review_cmt = $req->review;
    }

    public function getTourpackages(){
        // $data['tour'] = ToursModel::where('status',1)->orderBy('tour_id','desc')->paginate(6);
        $data['guider'] = guider::where('status',1)->get();
        $data['dest']   = destModel::all();
        $data['data'] = DB::table('Tours_tb')->where('Tours_tb.status',1)->join('traveltype_tb','Tours_tb.tour_id','=','traveltype_tb.tour_id')->orderBy('Tours_tb.tour_id','desc')->paginate(6);
        $data['banner'] = banner::where('banner_id',2)->first('banner_img');
        $data['sliCus'] = sliderCustomer::where('slider_status',1)->take(3)->get();
        return view('frontEnd.tour-packages',$data);
    }
    public function getpagetours(Request $request){
        if($request->ajax())
        {
            $data['data'] = DB::table('Tours_tb')->where('Tours_tb.status',1)->join('traveltype_tb','Tours_tb.tour_id','=','traveltype_tb.tour_id')->orderBy('Tours_tb.tour_id','desc')->paginate(6);
            return view('frontEnd.tours',$data);
        }
    }
}