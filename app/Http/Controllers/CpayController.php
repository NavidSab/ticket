<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\Cpay;
use App\Helper\CpayTools;
use App\Models\Menu;
use App\Models\MenuPermission;
class CpayController extends Controller
{


    public function index(Request $request)
    {
            $render_menu='';
            $menu_list=MenuPermission::getUserMenu(session()->get('userTypeId'));
            if(is_array($menu_list)){
            $render_menu=Menu::renderMenu($menu_list);
            }
            $menu_id = explode(',', $menu_list[0]['menu_id']);
            $data=[
                'name'       => session()->get('name'),
                'family'     => session()->get('family'),
                'sid'        => session()->get('sid'),
                'userTypeId' => session()->get('userTypeId'),
                'menu'       => $render_menu,
                'userId'     => session()->get('userId'),
                'menu_id'    => $menu_id
             ];
            return view('pages.dashboard',['data'=>$data]);
    } 
    public function callService(Request $request){
        $cpay=new Cpay();
        $cpay->doService($request->ac);
    }
    public function loadPage(Request $request){
        $data=[
            'name'       => session()->get('name'),
            'pageTitle'  => $request->pageTitle,
            'family'     => session()->get('family'),
            'sid'        => session()->get('sid'),
            'userTypeId' => session()->get('userTypeId'),
            'userId'     => session()->get('userId')
         ];
        return view('pages.'.$request->reportName,['data'=>$data]);
    }

    public function downloadZip(Request $request){
        if(!isset($request->key)){
          ('Nothing to see here.');
        }
        $data=[
            'key'       => $request->key
         ];
  
        $key =preg_replace('/\s+/', '', $data['key']);

        if(strlen($key) !== 32)
         die('Invalid key.');

        $files = glob(storage_path('cpay_csv'). '/*.zip');

        if(!$files)
                die('Files not available.');

        $zipFile = false;
        foreach($files as $file)
        {
                if($key == md5(basename($file)))
                {

                $zipFile = $file;
                        break;
                }
        }

        if(!$zipFile)
           die('Invalid or expired key.');

        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . basename($zipFile));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($zipFile));
        header('Content-Type: application/zip; charset=utf-8');
        // Send file to download
        readfile($zipFile);
        // Delete zip file
        unlink($zipFile);
    }

    public function reports(Request $request){
        $counter = 0;
        $smsOption=CpayTools::smsOption();
        if (!isset($request->reportName) ){
            return abort(404);
        }
        $thisReport = $request->reportName;
        $faTitle = $request->pageTitle;
        $recPerPageOpts = array("10", "15", "20");
        $reportsRef = array(
            "getTransByNID" => array(
                "formQueryFields" => array(
                    array(  "formElName" => "NID",
                            "formEltext" => "شماره ملی:",
                            "formElTagContent" => "<input name='NID' class='form-control' type='text' pattern='[0-9]{10}' required>"
                        )
                ),
                "recordNamePlural" => "تراکنش‌ها"),
             "GetListOfDebtors" => array(
                "formQueryFields" => array(
                    array( 
                         "formElName" => "minDebt",
                            "formEltext" => "حداقل بدهی:",
                            "formElTagContent" => "<input name='minDebt' type='text' class='amount form-control' type='text' placeholder='پیش‌فرض: یک ریال'>"
                        ),
                    array(  "formElName" => "maxDebt",
                            "formEltext" => "حداکثر بدهی:",
                            "formElTagContent" => "<input name='maxDebt' type='text' class='amount form-control' type='text'>")),
                 "recordNamePlural" => "بدهکاران"),
            "GetListOfDebtorsNoTag" => array(
                "formQueryFields" => array(
                    array(  "formElName" => "minDebt",
                        "formEltext" => "حداقل بدهی:",
                        "formElTagContent" => "<input name='minDebt' type='text' class='amount form-control' type='text' placeholder='پیش‌فرض: یک ریال'>"),
                    array(  "formElName" => "maxDebt",
                        "formEltext" => "حداکثر بدهی:",
                        "formElTagContent" => "<input name='maxDebt' type='text' class='amount form-control' type='text'>")),
                "recordNamePlural" => "بدهکاران"),
              "GetParkListByPlaque" => array(
                "formQueryFields" => array(
                        array(
                        "formElName" => "plaque",
                        "formEltext" => "پلاک",
                        "formElTagContent" =>
                        "<div class='col-sm-3' style='padding:0'>
                                  <div class='form-group'>
                                      <label for='aniro_plaque1' style='white-space:nowrap'>پلاک</label>
                                      <input type='text' name='p4' id='aniro_plaque4' class='form-control' tabindex='4' placeholder='ایران 11' pattern='[0-9]+' minlength='2' maxlength='2' required />
                                  </div>
                              </div>
                              <div class='col-sm-3' style='padding:0'>
                                  <div class='form-group'>
                                      <label>&nbsp;</label>
                                      <input type='text' name='p3' id='aniro_plaque3' class='form-control' tabindex='3' placeholder='---' pattern='[0-9]+' minlength='3' maxlength='3' required />
                                  </div>
                              </div>
                              <div class='col-sm-3' style='padding:0'>
                                  <div class='form-group'>
                                      <label>&nbsp;</label>
                                      <select name='pa' id='aniro_plaque2' class='form-control' tabindex='2' required>
                                      <option selected value data-code=''>---</option>
                                      <option data-code=1570>الف</option>
                                      <option data-code=1576>ب</option>
                                      <option data-code=1662>پ</option>
                                      <option data-code=1578>ت</option>
                                      <option data-code=1580>ج</option>
                                      <option data-code=1583>د</option>
                                      <option data-code=1587>س</option>
                                      <option data-code=1589>ص</option>
                                      <option data-code=1591>ط</option>
                                      <option data-code=1593>ع</option>
                                      <option data-code=1602>ق</option>
                                      <option data-code=1705>ک</option>
                                      <option data-code=1604>ل</option>
                                      <option data-code=1605>م</option>
                                      <option data-code=1606>ن</option>
                                      <option data-code=1608>و</option>
                                      <option data-code=1607>ه</option>
                                      <option data-code=1740>ي</option>
                                      <option data-code=1688>ژ</option>
                                      <option data-code=1711>گ</option>
                                      </select>
                                  </div>
                              </div>
                              <div class='col-sm-3' style='padding:0'>
                                  <div class='form-group'>
                                      <label>&nbsp;</label>
                                      <input type='text' name='p1' id='aniro_plaque1' class='form-control' tabindex='1' placeholder='--' pattern='[0-9]+' minlength='2' maxlength='2' required />
                                  </div>
                              </div>")
                         ),
                "recordNamePlural" => "پارک‌ها"
                ),
              "GetTransactions" => array(
                "formQueryFields" => array(
                    array(  "formElName" => "NID",
                            "formEltext" => "شماره ملی:",
                            "formElTagContent" => "<input name='NID' type='text'  class='form-control' pattern='[0-9]{10}' placeholder='همه'>"),
                    array(  "formElName" => "street",
                        "formEltext" => "خیابان:",
                        "formElTagContent" => "<select name='street' form='GetTransactionsForm' class='form-control'>
                        <option selected value data-code=''>-------</option>
                        <option data-code=1049>خیابان بعثت</option>
                        <option data-code=1050>خیابان جمالی</option>
                        <option data-code=11>خیابان ستارخان</option>
                        <option data-code=21>خیابان هفت تیر</option>
                        <option data-code=1028>خيابان خيام</option>
                        <option data-code=1042>خیابان لطفعلی خان زند</option>
                        <option data-code=1041>خیابان تختی</option>
                        <option data-code=13>خیابان پاسداران</option>
                        <option data-code=16>خیابان هدایت</option>
                        <option data-code=24>شهید بهشتی</option>
                        <option data-code=1037>خيابان هجرت</option>
                        <option data-code=12>خیابان معالی آباد</option>
                        <option data-code=1027>خيابان اهلي</option>
                        <option data-code=18>خیابان فلسطین</option>
                        <option data-code=8>خیابان مشکین فام</option>
                        <option data-code=1030>خيابان نشاط</option>
                        <option data-code=1045>خیابان شهید حراف</option>
                        <option data-code=25>خیابان بابا افضل</option>
                        <option data-code=14>خیابان صورتگر</option>
                        <option data-code=7>خیابان سمیه</option>
                        <option data-code=19>خیابان اردیبهشت</option>
                        <option data-code=1033>خيابان جهاد</option>
                        <option data-code=1036>خيابان حدادي</option>
                        <option data-code=1040>خیابان باب الحوائج</option>
                        <option data-code=17>خیابان پوستچی</option>
                        <option data-code=1046>خیابان رحمت</option>
                        <option data-code=1038>خیابان استاد رضا عباسی</option>
                        <option data-code=1032>خيابان ارم</option>
                        <option data-code=15>خیابان معدل</option>
                        <option data-code=1024>خيابان رودکي</option>
                        <option data-code=1026>خيابان انوري</option>
                        <option data-code=1035>خيابان حر</option>
                        <option data-code=1025>خيابان فردوسي</option>
                        <option data-code=1043>خیابان قاآنی نو</option>
                        <option data-code=6>خیابان عفیف آباد</option>
                        <option data-code=1031>خيابان آزادي</option>
                        <option data-code=1029>خيابان سعدي</option>
                        <option data-code=1047>خیابان عدالت</option>
                        <option data-code=1034>خيابان رباني</option>
                        <option data-code=1044>خیابان فخرآباد</option>
                        <option data-code=20>خیابان شوریده</option>
                        <option data-code=1023>نمایشگاه بین المللی تهران</option>
                        <option data-code=1048>خیابان وصال</option>
                        <option data-code=1039>خیابان میرعماد</option>
                        </select>"),
                    array(  
                         "formElName" => "startDateBox",
                        "formEltext" => " از:",
                        "formElTagContent" => "<input id='startDate' class='datepicker  form-control'  type='text'  placeholder='۳۰ روز پیش' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                    array(
                         "formElName" => "endDateBox",
                        "formEltext" => "تا:",
                        "formElTagContent" => "<input id='endDate' class='datepicker  form-control'  type='text'  placeholder='امروز' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                    array(
                         "formElName" => "plaque",
                         "formEltext" => "پلاک",
                         "formElTagContent" =>
                         "<div class='col-sm-3' style='padding:0'>
                                  <div class='form-group'>
                                      <label for='aniro_plaque1' style='white-space:nowrap'>پلاک</label>
                                      <input type='text' name='p4' id='aniro_plaque4' class='form-control' tabindex='4' placeholder='ایران 11' pattern='[0-9]+' minlength='2' maxlength='2' required />
                                  </div>
                              </div>
                              <div class='col-sm-3' style='padding:0'>
                                  <div class='form-group'>
                                      <label>&nbsp;</label>
                                      <input type='text' name='p3' id='aniro_plaque3' class='form-control' tabindex='3' placeholder='---' pattern='[0-9]+' minlength='3' maxlength='3' required />
                                  </div>
                              </div>
                              <div class='col-sm-3' style='padding:0'>
                                  <div class='form-group'>
                                      <label>&nbsp;</label>
                                      <select name='pa' id='aniro_plaque2' class='form-control' tabindex='2' required>
                                      <option selected value data-code=''>---</option>
                                      <option data-code=1570>الف</option>
                                      <option data-code=1576>ب</option>
                                      <option data-code=1662>پ</option>
                                      <option data-code=1578>ت</option>
                                      <option data-code=1580>ج</option>
                                      <option data-code=1583>د</option>
                                      <option data-code=1587>س</option>
                                      <option data-code=1589>ص</option>
                                      <option data-code=1591>ط</option>
                                      <option data-code=1593>ع</option>
                                      <option data-code=1602>ق</option>
                                      <option data-code=1705>ک</option>
                                      <option data-code=1604>ل</option>
                                      <option data-code=1605>م</option>
                                      <option data-code=1606>ن</option>
                                      <option data-code=1608>و</option>
                                      <option data-code=1607>ه</option>
                                      <option data-code=1740>ي</option>
                                      <option data-code=1688>ژ</option>
                                      <option data-code=1711>گ</option>
                                      </select>
                                  </div>
                              </div>
                              <div class='col-sm-3' style='padding:0'>
                                  <div class='form-group'>
                                      <label>&nbsp;</label>
                                      <input type='text' name='p1' id='aniro_plaque1' class='form-control' tabindex='1' placeholder='--' pattern='[0-9]+' minlength='2' maxlength='2' required />
                                  </div>
                              </div>"),
                        ),
               "recordNamePlural" => "اطلاعات"
                ),
               "sitePaymentsStats" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " از:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='۳۰ روز پیش' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "تا:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='امروز' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
                "recordNamePlural" => "ردیف‌ها"),
               "getStreetPark" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " از:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='۳۰ روز پیش' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "تا:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='امروز' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
                "recordNamePlural" => "ردیف‌ها"),
               "getParkLocation" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " از:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='۳۰ روز پیش' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "تا:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='امروز' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
                "recordNamePlural" => "ردیف‌ها"),
               "getWicketsStat" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " از:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='۳۰ روز پیش' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "تا:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='امروز' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
               "recordNamePlural" => "ردیف‌ها"),
               "getTotalTagsSold" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " از:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='۳۰ روز پیش' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "تا:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='امروز' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
               "recordNamePlural" => "ردیف‌ها"),       
               "GetStreetParkPriceCount" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " از:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='۳۰ روز پیش' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "تا:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='امروز' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
               "recordNamePlural" => "ردیف‌ها"),
               "GetDebtStatistics" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " از:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='۳۰ روز پیش' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "تا:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='امروز' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
               "recordNamePlural" => "ردیف‌ها"),
               "GetLoginStats" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " از:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='۳۰ روز پیش' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "تا:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='امروز' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
               "recordNamePlural" => "ردیف‌ها"),
               "GetStreetIncome" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " از:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='۳۰ روز پیش' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "تا:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='امروز' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
               "recordNamePlural" => "فایل‌ها"),
               "GetDailyIncome" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " از:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='۳۰ روز پیش' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "تا:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='امروز' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
                "recordNamePlural" => "ردیف‌ها"),
               "GetDailyCharge" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " از:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='۳۰ روز پیش' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "تا:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='امروز' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
                "recordNamePlural" => "ردیف‌ها"),
            );
                if (session()->get('userTypeId') == "20" && $thisReport == "smsPanel"){
                        $smsPanel =  array(
                                "formQueryFields" => array(
                                        array(  "formElName" => "minInventory",
                                                "formEltext" => "حداقل موجودی (+/-):",
                                                "formElTagContent" => "<input name='minInventory' class='amount form-control' type='text' >"),
                                        array(  "formElName" => "maxInventory",
                                                "formEltext" => "حداکثر موجودی (+/-):",
                                                "formElTagContent" => "<input name='maxInventory' class='amount form-control' type='text' placeholder='پیش‌فرض: صفر'>")),
                                "recordNamePlural" => "نتایج");
        
                        $reportsRef["smsPanel"] = $smsPanel;
        
                }
                else if(session()->get('userTypeId') == "26" && $thisReport == "smsPanelPolice")
                {
                        $smsPanelPolice =  array(
                                "formQueryFields" => array(
                                        array(  "formElName" => "minInventory",
                                                "formEltext" => "حداقل موجودی (+/-):",
                                                "formElTagContent" => "<input name='minInventory' class='amount form-control' type='text' >"),
                                        array(  "formElName" => "maxInventory",
                                                "formEltext" => "حداکثر موجودی (+/-):",
                                                "formElTagContent" => "<input name='maxInventory' class='amount form-control' type='text' placeholder='پیش‌فرض: صفر'>")),
                                "recordNamePlural" => "نتایج");
                        $reportsRef["smsPanelPolice"] = $smsPanelPolice;
                }


                $needCalendar = array("GetTransactions", "sitePaymentsStats", "getStreetPark", "getParkLocation", "getWicketsStat",
                "getTotalTagsSold", "GetStreetParkPriceCount", "GetDebtStatistics", "GetLoginStats",
                "GetStreetIncome", "GetDailyIncome", "GetDailyCharge");
                return view('pages.reports',['ReportName'=>$thisReport,'needCalendar'=>$needCalendar,'faTitle'=>$faTitle,'reportsRef'=>$reportsRef[$thisReport],'counter'=>$counter,'smsTexts'=>$smsOption['smsTexts'],'startOfMsg'=>$smsOption['startOfMsg'],'recPerPageOpts'=>$recPerPageOpts]);

        }

}
