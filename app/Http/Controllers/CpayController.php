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
                            "formEltext" => "?????????? ??????:",
                            "formElTagContent" => "<input name='NID' class='form-control' type='text' pattern='[0-9]{10}' required>"
                        )
                ),
                "recordNamePlural" => "???????????????????"),
             "GetListOfDebtors" => array(
                "formQueryFields" => array(
                    array( 
                         "formElName" => "minDebt",
                            "formEltext" => "?????????? ????????:",
                            "formElTagContent" => "<input name='minDebt' type='text' class='amount form-control' type='text' placeholder='???????????????: ???? ????????'>"
                        ),
                    array(  "formElName" => "maxDebt",
                            "formEltext" => "???????????? ????????:",
                            "formElTagContent" => "<input name='maxDebt' type='text' class='amount form-control' type='text'>")),
                 "recordNamePlural" => "????????????????"),
            "GetListOfDebtorsNoTag" => array(
                "formQueryFields" => array(
                    array(  "formElName" => "minDebt",
                        "formEltext" => "?????????? ????????:",
                        "formElTagContent" => "<input name='minDebt' type='text' class='amount form-control' type='text' placeholder='???????????????: ???? ????????'>"),
                    array(  "formElName" => "maxDebt",
                        "formEltext" => "???????????? ????????:",
                        "formElTagContent" => "<input name='maxDebt' type='text' class='amount form-control' type='text'>")),
                "recordNamePlural" => "????????????????"),
              "GetParkListByPlaque" => array(
                "formQueryFields" => array(
                        array(
                        "formElName" => "plaque",
                        "formEltext" => "????????",
                        "formElTagContent" =>
                        "<div class='col-sm-3' style='padding:0'>
                                  <div class='form-group'>
                                      <label for='aniro_plaque1' style='white-space:nowrap'>????????</label>
                                      <input type='text' name='p4' id='aniro_plaque4' class='form-control' tabindex='4' placeholder='?????????? 11' pattern='[0-9]+' minlength='2' maxlength='2' required />
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
                                      <option data-code=1570>??????</option>
                                      <option data-code=1576>??</option>
                                      <option data-code=1662>??</option>
                                      <option data-code=1578>??</option>
                                      <option data-code=1580>??</option>
                                      <option data-code=1583>??</option>
                                      <option data-code=1587>??</option>
                                      <option data-code=1589>??</option>
                                      <option data-code=1591>??</option>
                                      <option data-code=1593>??</option>
                                      <option data-code=1602>??</option>
                                      <option data-code=1705>??</option>
                                      <option data-code=1604>??</option>
                                      <option data-code=1605>??</option>
                                      <option data-code=1606>??</option>
                                      <option data-code=1608>??</option>
                                      <option data-code=1607>??</option>
                                      <option data-code=1740>??</option>
                                      <option data-code=1688>??</option>
                                      <option data-code=1711>??</option>
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
                "recordNamePlural" => "???????????????"
                ),
              "GetTransactions" => array(
                "formQueryFields" => array(
                    array(  "formElName" => "NID",
                            "formEltext" => "?????????? ??????:",
                            "formElTagContent" => "<input name='NID' type='text'  class='form-control' pattern='[0-9]{10}' placeholder='??????'>"),
                    array(  "formElName" => "street",
                        "formEltext" => "????????????:",
                        "formElTagContent" => "<select name='street' form='GetTransactionsForm' class='form-control'>
                        <option selected value data-code=''>-------</option>
                        <option data-code=1049>???????????? ????????</option>
                        <option data-code=1050>???????????? ??????????</option>
                        <option data-code=11>???????????? ??????????????</option>
                        <option data-code=21>???????????? ?????? ??????</option>
                        <option data-code=1028>???????????? ????????</option>
                        <option data-code=1042>???????????? ???????????? ?????? ??????</option>
                        <option data-code=1041>???????????? ????????</option>
                        <option data-code=13>???????????? ????????????????</option>
                        <option data-code=16>???????????? ??????????</option>
                        <option data-code=24>???????? ??????????</option>
                        <option data-code=1037>???????????? ????????</option>
                        <option data-code=12>???????????? ?????????? ????????</option>
                        <option data-code=1027>???????????? ????????</option>
                        <option data-code=18>???????????? ????????????</option>
                        <option data-code=8>???????????? ?????????? ??????</option>
                        <option data-code=1030>???????????? ????????</option>
                        <option data-code=1045>???????????? ???????? ????????</option>
                        <option data-code=25>???????????? ???????? ????????</option>
                        <option data-code=14>???????????? ????????????</option>
                        <option data-code=7>???????????? ????????</option>
                        <option data-code=19>???????????? ????????????????</option>
                        <option data-code=1033>???????????? ????????</option>
                        <option data-code=1036>???????????? ??????????</option>
                        <option data-code=1040>???????????? ?????? ??????????????</option>
                        <option data-code=17>???????????? ????????????</option>
                        <option data-code=1046>???????????? ????????</option>
                        <option data-code=1038>???????????? ?????????? ?????? ??????????</option>
                        <option data-code=1032>???????????? ??????</option>
                        <option data-code=15>???????????? ????????</option>
                        <option data-code=1024>???????????? ??????????</option>
                        <option data-code=1026>???????????? ??????????</option>
                        <option data-code=1035>???????????? ????</option>
                        <option data-code=1025>???????????? ????????????</option>
                        <option data-code=1043>???????????? ?????????? ????</option>
                        <option data-code=6>???????????? ???????? ????????</option>
                        <option data-code=1031>???????????? ??????????</option>
                        <option data-code=1029>???????????? ????????</option>
                        <option data-code=1047>???????????? ??????????</option>
                        <option data-code=1034>???????????? ??????????</option>
                        <option data-code=1044>???????????? ??????????????</option>
                        <option data-code=20>???????????? ????????????</option>
                        <option data-code=1023>???????????????? ?????? ???????????? ??????????</option>
                        <option data-code=1048>???????????? ????????</option>
                        <option data-code=1039>???????????? ??????????????</option>
                        </select>"),
                    array(  
                         "formElName" => "startDateBox",
                        "formEltext" => " ????:",
                        "formElTagContent" => "<input id='startDate' class='datepicker  form-control'  type='text'  placeholder='???? ?????? ??????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                    array(
                         "formElName" => "endDateBox",
                        "formEltext" => "????:",
                        "formElTagContent" => "<input id='endDate' class='datepicker  form-control'  type='text'  placeholder='??????????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                    array(
                         "formElName" => "plaque",
                         "formEltext" => "????????",
                         "formElTagContent" =>
                         "<div class='col-sm-3' style='padding:0'>
                                  <div class='form-group'>
                                      <label for='aniro_plaque1' style='white-space:nowrap'>????????</label>
                                      <input type='text' name='p4' id='aniro_plaque4' class='form-control' tabindex='4' placeholder='?????????? 11' pattern='[0-9]+' minlength='2' maxlength='2' required />
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
                                      <option data-code=1570>??????</option>
                                      <option data-code=1576>??</option>
                                      <option data-code=1662>??</option>
                                      <option data-code=1578>??</option>
                                      <option data-code=1580>??</option>
                                      <option data-code=1583>??</option>
                                      <option data-code=1587>??</option>
                                      <option data-code=1589>??</option>
                                      <option data-code=1591>??</option>
                                      <option data-code=1593>??</option>
                                      <option data-code=1602>??</option>
                                      <option data-code=1705>??</option>
                                      <option data-code=1604>??</option>
                                      <option data-code=1605>??</option>
                                      <option data-code=1606>??</option>
                                      <option data-code=1608>??</option>
                                      <option data-code=1607>??</option>
                                      <option data-code=1740>??</option>
                                      <option data-code=1688>??</option>
                                      <option data-code=1711>??</option>
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
               "recordNamePlural" => "??????????????"
                ),
               "sitePaymentsStats" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " ????:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='???? ?????? ??????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "????:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='??????????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
                "recordNamePlural" => "???????????????"),
               "getStreetPark" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " ????:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='???? ?????? ??????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "????:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='??????????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
                "recordNamePlural" => "???????????????"),
               "getParkLocation" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " ????:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='???? ?????? ??????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "????:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='??????????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
                "recordNamePlural" => "???????????????"),
               "getWicketsStat" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " ????:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='???? ?????? ??????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "????:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='??????????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
               "recordNamePlural" => "???????????????"),
               "getTotalTagsSold" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " ????:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='???? ?????? ??????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "????:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='??????????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
               "recordNamePlural" => "???????????????"),       
               "GetStreetParkPriceCount" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " ????:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='???? ?????? ??????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "????:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='??????????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
               "recordNamePlural" => "???????????????"),
               "GetDebtStatistics" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " ????:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='???? ?????? ??????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "????:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='??????????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
               "recordNamePlural" => "???????????????"),
               "GetLoginStats" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " ????:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='???? ?????? ??????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "????:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='??????????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
               "recordNamePlural" => "???????????????"),
               "GetStreetIncome" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " ????:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='???? ?????? ??????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "????:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='??????????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
               "recordNamePlural" => "???????????????"),
               "GetDailyIncome" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " ????:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='???? ?????? ??????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "????:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='??????????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
                "recordNamePlural" => "???????????????"),
               "GetDailyCharge" => array(
                "formQueryFields" => array(
                        array(  "formElName" => "startDateBox",
                                "formEltext" => " ????:",
                                "formElTagContent" => "<input id='startDate' class='datepicker datepicker form-control'  type='text'  placeholder='???? ?????? ??????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>"),
                        array(  "formElName" => "endDateBox",
                                "formEltext" => "????:",
                                "formElTagContent" => "<input id='endDate' class='datepicker datepicker form-control'  type='text'  placeholder='??????????' pattern='[1-9]{4}\/[0-9]{2}\/[0-9]{2}' readonly>")),
                "recordNamePlural" => "???????????????"),
            );
                if (session()->get('userTypeId') == "20" && $thisReport == "smsPanel"){
                        $smsPanel =  array(
                                "formQueryFields" => array(
                                        array(  "formElName" => "minInventory",
                                                "formEltext" => "?????????? ???????????? (+/-):",
                                                "formElTagContent" => "<input name='minInventory' class='amount form-control' type='text' >"),
                                        array(  "formElName" => "maxInventory",
                                                "formEltext" => "???????????? ???????????? (+/-):",
                                                "formElTagContent" => "<input name='maxInventory' class='amount form-control' type='text' placeholder='???????????????: ??????'>")),
                                "recordNamePlural" => "??????????");
        
                        $reportsRef["smsPanel"] = $smsPanel;
        
                }
                else if(session()->get('userTypeId') == "26" && $thisReport == "smsPanelPolice")
                {
                        $smsPanelPolice =  array(
                                "formQueryFields" => array(
                                        array(  "formElName" => "minInventory",
                                                "formEltext" => "?????????? ???????????? (+/-):",
                                                "formElTagContent" => "<input name='minInventory' class='amount form-control' type='text' >"),
                                        array(  "formElName" => "maxInventory",
                                                "formEltext" => "???????????? ???????????? (+/-):",
                                                "formElTagContent" => "<input name='maxInventory' class='amount form-control' type='text' placeholder='???????????????: ??????'>")),
                                "recordNamePlural" => "??????????");
                        $reportsRef["smsPanelPolice"] = $smsPanelPolice;
                }


                $needCalendar = array("GetTransactions", "sitePaymentsStats", "getStreetPark", "getParkLocation", "getWicketsStat",
                "getTotalTagsSold", "GetStreetParkPriceCount", "GetDebtStatistics", "GetLoginStats",
                "GetStreetIncome", "GetDailyIncome", "GetDailyCharge");
                return view('pages.reports',['ReportName'=>$thisReport,'needCalendar'=>$needCalendar,'faTitle'=>$faTitle,'reportsRef'=>$reportsRef[$thisReport],'counter'=>$counter,'smsTexts'=>$smsOption['smsTexts'],'startOfMsg'=>$smsOption['startOfMsg'],'recPerPageOpts'=>$recPerPageOpts]);

        }

}
