<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

//Route::get('/admin', 'AdminController@index');
Route::get('/', 'AdminController@index');

Route::group(['prefix'=>'admin','middleware' => 'revalidate'],function(){
  Route::get('/','AdminController@index')->name('admin.dashboard');
  Route::get('/login','Auth\AdminLoginController@showLoginForm')->name('admin.login');
  Route::post('/login','Auth\AdminLoginController@login')->name('admin.login.submit');
  Route::get('/logout','AdminController@logout')->name('admin.logout');

  /***********************Admin Start**************************************/
  Route::get('/adminslist','AdminController@list')->name('admin.list');
  Route::get('/adminslist/search/post', 'AdminController@searchpost')->name('admin.search');
  Route::get('/admincreate','AdminController@create')->name('admin.create');
  Route::post('/adminsstore','AdminController@store')->name('admin.store');
  Route::get('/adminedit/{id}','AdminController@edit')->name('admin.edit');
  Route::post('/admin/{id}/update','AdminController@update')->name('admin.update');
  Route::post('/admin/{id}/delete', 'AdminController@destory')->name('admin.delete');
  /***********************Admin End**************************************/

  /***********************Role Start**************************************/
  Route::get('/Rolelist','RoleController@list')->name('role.list');
  Route::get('/Rolelist/search/post', 'RoleController@searchrole')->name('role.search');
  Route::get('/Rolelist/create', 'RoleController@create')->name('role.create');
  Route::post('/RoleStore','RoleController@store')->name('role.store');
  Route::get('/Rolelist/edit/{id}', 'RoleController@edit')->name('role.edit');
 
  Route::post('/Rolelist/{id}/update','RoleController@update')->name('role.update');
  Route::post('/Rolelist/{id}/delete', 'RoleController@destory')->name('role.delete');
  /***********************Role End**************************************/

  /***********************Agent Start**************************************/
  Route::get('/Agentlist','AgentController@list')->name('agent.list');
  Route::get('/Agentlist/search/post', 'AgentController@searchagent')->name('agent.search');
  Route::post('/AgentStore','AgentController@store')->name('agent.store');
  Route::post('/Agentlist/{id}/update','AgentController@update')->name('agent.update');
  Route::post('/Agentlist/{id}/delete', 'AgentController@destory')->name('agent.delete');
  /***********************Agent End**************************************/

  /***********************Category Start*************************************/
  Route::get('/FTTH','CategoryController@index')->name('category.list'); 
  Route::post('/FTTHStore','CategoryController@store')->name('category.submit');
  Route::get('/FTTH/search/post', 'CategoryController@searchpost')->name('category.search');
  Route::post('/FTTH/{id}/delete', 'CategoryController@destroy')->name('category.delete');
  Route::post('/FTTH/{id}/edit','CategoryController@update')->name('category.edit.submit');
  /***********************Category End*************************************/

  /***********************ItemName Start*************************************/
  Route::get('/{title}/ItemNameList','ItemnameController@index')->name('itemname.list');
  Route::post('/ItemnameEntry','ItemnameController@store')->name('itemname.store.submit');
  Route::post('/ItemnameList/{id}/edit','ItemnameController@update')->name('itemname.edit.submit');
  Route::post('/ItemnameList/{id}/delete', 'ItemnameController@destroy')->name('itemname.delete');
  /***********************ItemName End*************************************/

  /***********************Store Start*************************************/
  Route::get('/storelist','StoreController@index')->name('store.list');
  Route::post('/StoreEntry','StoreController@store')->name('store.entry.submit');
  Route::post('/Store/{id}/edit','StoreController@update')->name('store.edit.submit');
  Route::post('/Store/{id}/delete', 'StoreController@destroy')->name('store.delete');
  Route::get('/storelist/search/post', 'StoreController@searchpost')->name('store.search');
  /***********************Store End*************************************/

  /***********************Supplier Start*************************************/
  Route::get('/SupplierList','SupplierController@index')->name('supplier.list');
  Route::get('/SupplierList/search/post', 'SupplierController@searchpost')->name('supplier.search');
  Route::post('/SupplierListEntry','SupplierController@store')->name('supplier.store.submit');
  Route::post('/SupplierList/{id}/delete', 'SupplierController@destroy')->name('supplier.delete');
  Route::post('/SupplierList/{id}/edit','SupplierController@update')->name('supplier.edit.submit');
  /***********************Supplier End*************************************/

  /***********************Staff Start*************************************/
  Route::get('/StaffList','StaffController@index')->name('staff.list');
  Route::get('/StaffList/search/post', 'StaffController@searchpost')->name('staff.search');
  Route::post('/StaffListEntry','StaffController@store')->name('staff.store.submit');
  Route::post('/StaffList/{id}/delete', 'StaffController@destroy')->name('staff.delete');
  Route::post('/StaffList/{id}/edit','StaffController@update')->name('staff.edit.submit');
  /***********************Staff End*************************************/

  /***********************Server Name Start*************************************/
  Route::get('/ServerNameList','ServernameController@index')->name('servername.list');
  Route::get('/ServerNameList/search/post', 'ServernameController@searchpost')->name('servername.search');
  Route::post('/ServerNameListEntry','ServernameController@store')->name('servername.store.submit');
  Route::post('/ServerNameList/{id}/delete', 'ServernameController@destroy')->name('servername.delete');
  Route::post('/ServerNameList/{id}/edit','ServernameController@update')->name('servername.edit.submit');
  /***********************Server Name End*************************************/

  /***********************Job Title Start*************************************/
  Route::get('/JobtitleList','JobtitleController@index')->name('jobtitle.list');
  Route::get('/JobtitleList/search/post', 'JobtitleController@searchpost')->name('jobtitle.search');
  Route::post('/JobtitleEntry','JobtitleController@store')->name('jobtitle.store.submit');
  Route::post('/JobtitleList/{id}/delete', 'JobtitleController@destroy')->name('jobtitle.delete');
  Route::post('/JobtitleList/{id}/edit','JobtitleController@update')->name('jobtitle.edit.submit');
  /***********************Job Title End*************************************/

  /***********************Items Buy Start*************************************/
  Route::get('/ItemsBuyList','ItemController@itemsbuylist')->name('itemsbuy.list');
  Route::get('/ItemsBuyList/Entry','ItemController@entry')->name('itemsbuy.entry');
  Route::get('/ItemsBuyList/itemnames/get/{id}', 'ItemController@getitemname'); 

  Route::get('/ItemsBuyList/addtoitemsbuy/{itemname_id}/{category_id}/{qty}/{amount}/{mac}', 'ItemController@additemsbuy')->name('additemsbuy');
  Route::get('/ItemsBuyList/addtoitemsbuyupdate/{itemname_id}/{category_id}/{qty}/{amount}/{mac}', 'ItemController@additemsbuyupdate')->name('additemsbuyupdate');
  Route::get('/ItemsBuyList/removeitemsbuy/{id}','ItemController@remove_itemsbuy')->name('remove_itemsbuy');
  Route::post('/ItemsBuyList/checkout','ItemController@itemsbuycheckout')->name('itemsbuycheckout');
  Route::get('/ItemsBuyList/search/post', 'ItemController@searchitemsbuy')->name('searchitemsbuy.search');

  Route::get('/ItemsBuyList/{voucher_code}', 'ItemController@viewdetail')->name('ItemsBuyList.viewdetail');
  Route::post('/ItemsBuyList/{id}/viewdetailedit', 'ItemController@viewdetailedit')->name('ItemsBuyList.viewdetailedit');
  Route::post('/ItemsBuyList/{id}/viewdetaildelete', 'ItemController@viewdetaildelete')->name('ItemsBuyList.viewdetaildelete');

  Route::post('/ItemsBuyList/{id}/delete', 'ItemController@itemsbuydestroy')->name('ItemsBuyList.delete');
  Route::get('/ItemsBuyList/{voucher_code}/Edit', 'ItemController@itemsbuyedit')->name('itemsbuy.edit');
  Route::post('/ItemsBuyList/{voucher_code}/Edit', 'ItemController@itemsbuyupdate')->name('itemsbuy.update');

  Route::get('/ItemsBuyList/removeolditemsbuy/{itemname_id}/{voucher_code}','ItemController@removeold_itemsbuy')->name('remove_olditemsbuy');
  Route::get('/ItemsBuyList/removeitemsbuyupdate/{id}','ItemController@remove_itemsbuyupdate')->name('remove_itemsbuyupdate');

  Route::get('/{title}/{name}/ViewList/{storename}','ItemController@show')->name('item.show');
  Route::get('/{title}/{name}/ViewListItem/{storename}','ItemController@show1')->name('item.show1');
  Route::get('/{title}/{name}/ViewList/{storename}/search/post', 'ItemController@searchpost')->name('item.search');

  Route::post('/ItemsBuyList/{id}/viewdetailitemedit', 'ItemController@viewdetailitemedit')->name('ItemsBuyList.viewdetailitemedit');
  Route::post('/ItemsBuyList/{id}/viewdetailitemdelete', 'ItemController@viewdetailitemdelete')->name('ItemsBuyList.viewdetailitemdelete');
  /***********************Items Buy Start*************************************/

  /***********************Items Server Start*************************************/
  Route::get('/ItemsServerList','ServerController@index')->name('servers.list');
  Route::get('/ItemsServerList/search/list', 'ServerController@searchlist')->name('serverslist.search'); 
  Route::get('/ItemsServerList/{name}/View', 'ServerController@serverviewshow')->name('serversview.show');
  Route::get('/ItemsServerList/{name}/search/view', 'ServerController@searchviewshow')->name('serverssearchview.show');
  Route::get('/ItemsServerList/{name}/ViewAllDetail', 'ServerController@serverviewalldetail')->name('serversview.alldetail');
  /**Confirm Start**/
  Route::get('/ItemsServerList/{id}/{name}/Confirm', 'ServerController@serverdetailconfirm')->name('serverdetail.confirm'); 
  Route::get('/ItemsServerList/{id}/{name}/RedoConfirm', 'ServerController@serverdetailredoconfirm')->name('serverdetail.redoconfirm'); 
  /**Confirm End**/
  Route::get('/ItemsServerList/ViewDetail/{name}/{server_number}', 'ServerController@serverviewdetailshow')->name('serversviewdetail.show');
  Route::post('/ItemsServerList/ViewDetail/ItemByItemServerUpdate/{id}', 'ServerController@itembyitemserverupdate')->name('ItemByItemServerUpdate');

  Route::post('/ItemsServerList/ViewDetail/ItemByItemServerCountUpdate/{itemname_id}/{server_id}', 'ServerController@itembyitemservercountupdate')->name('ItemByItemServerCountUpdate');
  Route::post('/ItemsServerList/ViewDetail/ItemByItemServerRedoCountUpdate/{itemname_id}/{server_id}', 'ServerController@itembyitemserverredocountupdate')->name('ItemByItemServerRedoCountUpdate');

  Route::post('/ItemsServerList/ViewAllDetail/ItemByItemServerCountUpdateAllDetail/{itemname_id}', 'ServerController@itembyitemservercountupdatealldetail')->name('itembyitemservercountupdatealldetail');
  Route::post('/ItemsServerList/ViewAllDetail/ItemByItemServerRedoCountUpdateAllDetail/{itemname_id}', 'ServerController@itembyitemserverredocountupdatealldetail')->name('ItemByItemServerRedoCountUpdateAllDetail');

  Route::get('/ItemsServerEntry','ServerController@create')->name('servers.create');
  Route::get('/ItemsServerEntry/search/post', 'ServerController@searchpost')->name('servers.search');
  Route::get('/ItemsServerEntrySerial/serial/{itemname_id}/{store_id}', 'ServerController@saveserial')->name('servers.saveserial');
  Route::get('/ItemsServerEntrySerial/additemserverserial/{id}/{store_id}', 'ServerController@additemserverserial')->name('servers.additemserverserial');

   Route::get('ItemsServerEntry/remove_itemserversitems/{id}/','ServerController@remove_itemserversitems')->name('remove_itemserversitems');

  Route::get('/ItemsServerEntrySerial/mac/{itemname_id}/{store_id}', 'ServerController@savemac')->name('servers.savemac');
  Route::get('/ItemsServerEntrySerial/additemservermac/{id}/{store_id}', 'ServerController@additemservermac')->name('servers.additemservermac');

  Route::get('ItemsServerEntry/additemsservercount/{id}/{store_id}/{count}', 'ServerController@additemsservercount')->name('additemsservercount');

  Route::get('/ItemsServerEntry/getstaffdata/get/{id}', 'ServerController@getstaffdata') ;

  Route::post('ItemsServerEntry/ItemsServerCheckout/{store_id}','ServerController@itemsservercheckout')->name('itemsservercheckout');

  Route::post('/ItemsServerList/{id}/Delete', 'ServerController@destroy')->name('server.delete');

  Route::get('/ItemsServerList/{server_number}/ServerUpdate', 'ServerController@edit')->name('server.edit'); 
  Route::post('/ItemsServerList/{server_number}/ServerUpdate', 'ServerController@update')->name('server.update');

  Route::get('/ItemsServerList/removeolditemssamdm/{itemname_id}/{id}/{server_id}','ServerController@removeolditemssamdm')->name('removeolditemssamdm');
  // Route::get('/ItemsServerList/removeolditemscount/{itemname_id}/{server_id}','ServerController@removeolditemscount')->name('removeolditemscount');

  Route::get('/ItemsServerEntry/test', 'ServerController@test')->name('test');

  /***********************Items Server End*************************************/

  /***********************Items Street Start*************************************/
  Route::get('/ItemsStreetList','StreetController@index')->name('streets.list');
  Route::get('/ItemsStreetList/search/list', 'StreetController@searchlist')->name('streetslist.search'); 
  Route::get('/ItemsStreetList/{name}/View', 'StreetController@streetviewshow')->name('streetview.show');
  Route::get('/ItemsStreetList/{name}/search/view', 'StreetController@searchviewshow')->name('streetssearchview.show');
  Route::get('/ItemsStreetList/ViewDetail/{name}/{street_number}', 'StreetController@streetviewdetailshow')->name('streetsviewdetail.show');

  Route::get('/ItemsStreetList/{name}/ViewAllDetail', 'StreetController@streetviewalldetail')->name('streetsview.alldetail');

  /**Confirm Start**/
  Route::get('/ItemsStreetList/{id}/{name}/Confirm', 'StreetController@streetdetailconfirm')->name('streetdetail.confirm'); 
  Route::get('/ItemsStreetList/{id}/{name}/RedoConfirm', 'StreetController@streetdetailredoconfirm')->name('streetdetail.redoconfirm');  
  /**Confirm End**/
  
  /**Item Update Start**/
  Route::post('/ItemsStreetList/ViewDetail/ItemByItemStreetUpdate/{id}', 'StreetController@itembyitemstreetupdate')->name('ItemByItemStreetUpdate');
  Route::post('/ItemsStreetList/ViewDetail/ItemByItemStreetCountUpdate/{itemname_id}/{street_id}', 'StreetController@itembyitemstreetcountupdate')->name('ItemByItemStreetCountUpdate');
  Route::post('/ItemsStreetList/ViewDetail/ItemByItemStreetRedoCountUpdate/{itemname_id}/{street_id}', 'StreetController@itembyitemstreetredocountupdate')->name('ItemByItemStreetRedoCountUpdate');  

  Route::post('/ItemsStreetList/ViewAllDetail/ItemByItemStreetCountUpdateAllDetail/{itemname_id}', 'StreetController@itembyitemstreetcountupdatealldetail')->name('itembyitemstreetcountupdatealldetail');
  Route::post('/ItemsStreetList/ViewAllDetail/ItemByItemStreetRedoCountUpdateAllDetail/{itemname_id}', 'StreetController@itembyitemstreetredocountupdatealldetail')->name('ItemByItemStreetRedoCountUpdateAllDetail');

  /**Item Update End**/

  /*****Create Strat*****/
  Route::get('/ItemsStreetEntry','StreetController@create')->name('street.create');
  Route::get('/ItemsStreetEntry/search/post', 'StreetController@searchpost')->name('streets.search');

  Route::get('/ItemsStreetEntrySerial/serial/{itemname_id}/{store_id}', 'StreetController@savestreetserial')->name('street.saveserial');
  Route::get('/ItemsStreetEntrySerial/additemstreetserial/{id}/{store_id}', 'StreetController@additemstreetserial')->name('streets.additemstreetserial');

  Route::get('/ItemsStreetEntrySerial/mac/{itemname_id}/{store_id}', 'StreetController@savestreetmac')->name('street.savemac');
  Route::get('/ItemsStreetEntrySerial/additemstreetmac/{id}/{store_id}', 'StreetController@additemstreetmac')->name('street.additemstreetmac');
   

  Route::get('ItemsStreetEntry/additemsstreetcount/{id}/{store_id}/{count}', 'StreetController@additemsstreetcount')->name('additemsstreetcount');

  Route::get('ItemsStreetEntry/remove_itemstreetsitems/{id}/','StreetController@remove_itemstreetsitems')->name('remove_itemstreetsitems');

  Route::get('/ItemsStreetEntry/getstaffdata/get/{id}', 'StreetController@getstreetstaffdata') ;

  Route::post('ItemsStreetEntry/ItemsStreetCheckout/{store_id}','StreetController@itemstreetcheckout')->name('itemstreetcheckout');
  /***Create End***/

  /*****Update Start***/
  Route::get('/ItemsStreetList/{street_number}/StreetUpdate', 'StreetController@edit')->name('street.edit'); 
  Route::post('/ItemsStreetList/{street_number}/StreetUpdate', 'StreetController@update')->name('street.update');

 Route::get('/ItemsStreetList/removeolditemssamdmstreet/{itemname_id}/{id}/{street_id}','StreetController@removeolditemssamdmstreet')->name('removeolditemssamdmstreet');
  /*****Update End***/

 Route::post('/ItemsStreetList/{id}/Delete', 'StreetController@destroy')->name('street.delete');

  /***********************Items Street End*************************************/

  /***********************Items Customer Start*************************************/
  Route::get('/ItemsCustomerList','CustomerController@index')->name('customers.list');
  Route::get('/ItemsCustomerList/search/list', 'CustomerController@searchlist')->name('customerslist.search'); 

  Route::get('/ItemsCustomerList/{code}/View', 'CustomerController@customerviewshow')->name('customerview.show');
  Route::get('/ItemsCustomerList/{code}/search/view', 'CustomerController@searchviewshow')->name('customerssearchview.show');

  Route::get('/ItemsCustomerList/ViewDetail/{code}/{customer_number}', 'CustomerController@customerviewdetailshow')->name('customersviewdetail.show');

  Route::get('/ItemsCustomerList/{code}/ViewAllDetail', 'CustomerController@customerviewalldetail')->name('customersview.alldetail');

  /**Confirm Start**/
  Route::get('/ItemsCustomerList/{id}/{code}/Confirm', 'CustomerController@customerdetailconfirm')->name('customerdetail.confirm'); 
  Route::get('/ItemsCustomerList/{id}/{code}/RedoConfirm', 'CustomerController@customerdetailredoconfirm')->name('customerdetail.redoconfirm');  
  /**Confirm End**/

  /**Item Update Start**/
  Route::post('/ItemsCustomerList/ViewDetail/ItemByItemCustomerUpdate/{id}', 'CustomerController@itembyitemcustomerupdate')->name('ItemByItemCustomerUpdate');
  Route::post('/ItemsCustomerList/ViewDetail/ItemByItemCustomerCountUpdate/{itemname_id}/{customer_id}', 'CustomerController@itembyitemcustomercountupdate')->name('ItemByItemCustomerCountUpdate');
  Route::post('/ItemsCustomerList/ViewDetail/ItemByItemCustomerRedoCountUpdate/{itemname_id}/{customer_id}', 'CustomerController@itembyitemcustomerredocountupdate')->name('ItemByItemCustomerRedoCountUpdate');  
  Route::post('/ItemsCustomerList/ViewAllDetail/ItemByItemCustomerCountUpdateAllDetail/{itemname_id}', 'CustomerController@itembyitemcustomercountupdatealldetail')->name('itembyitemcustomercountupdatealldetail');
  Route::post('/ItemsCustomerList/ViewAllDetail/ItemByItemCustomerRedoCountUpdateAllDetail/{itemname_id}', 'CustomerController@itembyitemcustomerredocountupdatealldetail')->name('ItemByItemCustomerRedoCountUpdateAllDetail');

  /**Item Update End**/

  /*****Create Strat*****/
  Route::get('/ItemsCustomerEntry','CustomerController@create')->name('customer.create');
  Route::get('/ItemsCustomerEntry/search/post', 'CustomerController@searchpost')->name('customers.search');

  Route::get('/ItemsCustomerEntrySerial/serial/{itemname_id}/{store_id}', 'CustomerController@savecustomerserial')->name('customer.saveserial');
  Route::get('/ItemsCustomerEntrySerial/additemcustomerserial/{id}/{store_id}', 'CustomerController@additemcustomerserial')->name('customers.additemcustomerserial');

  Route::get('/ItemsCustomerEntrySerial/mac/{itemname_id}/{store_id}', 'CustomerController@savecustomermac')->name('customer.savemac');
  Route::get('/ItemsCustomerEntrySerial/additemcustomermac/{id}/{store_id}', 'CustomerController@additemcustomermac')->name('customer.additemcustomermac');

  Route::get('ItemsCustomerEntry/additemscustomercount/{id}/{store_id}/{count}', 'CustomerController@additemscustomercount')->name('additemscustomercount');

  Route::get('ItemsCustomerEntry/remove_itemcustomersitems/{id}/','CustomerController@remove_itemcustomersitems')->name('remove_itemcustomersitems');

  Route::get('ItemsCustomerEntry/getCustomerName/{storename}','CustomerController@getcustomernamelist')->name('getcustomername_list');
  Route::get('/ItemsCustomerEntry/search/list/{storename}', 'CustomerController@getsearchlistcustomername')->name('getsearchlistcustomername.search');
  Route::get('/ItemsCustomerEntry/{storename}/{customernamecode}', 'CustomerController@getstoreandcustomername')->name('getstoreandcustomername.get');

  Route::get('/ItemsCustomerEntry/getstaffdata/get/{id}', 'CustomerController@getcustomerstaffdata') ;

  Route::post('ItemsCustomerEntry/ItemsCustomerCheckout/{store_id}','CustomerController@itemcustomercheckout')->name('itemcustomercheckout');

  /*****Create End*****/

  /*****Update Start***/
  Route::get('/ItemsCustomerList/{customer_number}/CustomerUpdate', 'CustomerController@edit')->name('customer.edit');
  Route::post('/ItemsCustomerList/{customer_number}/CustomerUpdate', 'CustomerController@update')->name('customer.update');

  Route::get('/ItemsCustomerList/removeolditemssamdmcustomer/{itemname_id}/{id}/{customer_id}','CustomerController@removeolditemssamdmcustomer')->name('removeolditemssamdmcustomer');

  Route::get('ItemsCustomerList/getCustomerNameUpdate/{storename}/{customer_id}','CustomerController@getcustomernamelistupdate')->name('getcustomername_listupdate');
  Route::get('/ItemsCustomerList/searchupdate/list/{storename}/{customer_id}', 'CustomerController@getsearchlistcustomernameupdate')->name('getsearchlistcustomernameupdate.search');
  Route::get('/ItemsCustomerList/{customer_id}/{customernamecode}/Update', 'CustomerController@getstoreandcustomernameupdate')->name('getstoreandcustomernameupdate.get'); 
  /*****Update End***/

  Route::post('/ItemsCustomerList/{id}/Delete', 'CustomerController@destroy')->name('customer.delete');
  /***********************Items Customer End*************************************/

  /***********************Items Transfer Start*************************************/
  Route::get('/ItemsTransferList','TransferController@index')->name('transfers.list');
  Route::get('/ItemsTransferList/search/list', 'TransferController@searchlist')->name('transferslist.search'); 

  Route::get('/ItemsTransferList/{id}/{name}/View', 'TransferController@transferviewshow')->name('transferview.show');
  Route::get('/ItemsTransferList/{id}/{name}/search/view', 'TransferController@searchviewshow')->name('transferssearchview.show');

  Route::get('/ItemsTransferList/ViewDetail/{id}/{name}/{transfer_number}', 'TransferController@transferviewdetailshow')->name('transfersviewdetail.show');

  Route::get('/ItemsTransferList/{id}/{name}/ViewAllDetail', 'TransferController@transferviewalldetail')->name('transfersview.alldetail');

  /**Item Update Start**/
  Route::post('/ItemsTransferList/ViewDetail/ItemByItemTransferUpdate/{id}', 'TransferController@itembyitemtransferupdate')->name('ItemByItemTransferUpdate');
  Route::post('/ItemsTransferList/ViewDetail/ItemByItemTransferCountUpdate/{itemname_id}/{transfer_id}', 'TransferController@itembyitemtransfercountupdate')->name('ItemByItemTransferCountUpdate');
  Route::post('/ItemsTransferList/ViewDetail/ItemByItemTransferRedoCountUpdate/{itemname_id}/{transfer_id}', 'TransferController@itembyitemtransferredocountupdate')->name('ItemByItemTransferRedoCountUpdate');  

  Route::post('/ItemsTransferList/ViewAllDetail/ItemByItemTransferCountUpdateAllDetail/{itemname_id}', 'TransferController@itembyitemtransfercountupdatealldetail')->name('itembyitemtransfercountupdatealldetail');

  Route::post('/ItemsTransferList/ViewAllDetail/ItemByItemTransferRedoCountUpdateAllDetail/{itemname_id}', 'TransferController@itembyitemtransferredocountupdatealldetail')->name('ItemByItemTransferRedoCountUpdateAllDetail');

  // Route::post('/ItemsStreetList/ViewAllDetail/ItemByItemStreetCountUpdateAllDetail/{itemname_id}', 'StreetController@itembyitemstreetcountupdatealldetail')->name('itembyitemstreetcountupdatealldetail');
  // Route::post('/ItemsStreetList/ViewAllDetail/ItemByItemStreetRedoCountUpdateAllDetail/{itemname_id}', 'StreetController@itembyitemstreetredocountupdatealldetail')->name('ItemByItemStreetRedoCountUpdateAllDetail');

  /**Item Update End**/


  /*****Create Strat*****/
  Route::get('/ItemsTransferEntry','TransferController@create')->name('transfer.create');
  Route::get('/ItemsTransferEntry/search/post', 'TransferController@searchpost')->name('transfers.search');

  Route::get('/ItemsTransferEntrySerial/serial/{itemname_id}/{store_id}', 'TransferController@savetransferserial')->name('transfer.saveserial');
  Route::get('/ItemsTransferEntrySerial/additemtransferserial/{id}/{store_id}', 'TransferController@additemtransferserial')->name('transfers.additemtransferserial');

  Route::get('/ItemsTransferEntrySerial/mac/{itemname_id}/{store_id}', 'TransferController@savetransfermac')->name('transfer.savemac');
  Route::get('/ItemsTransferEntrySerial/additemtransfermac/{id}/{store_id}', 'TransferController@additemtransfermac')->name('transfer.additemtransfermac');

  Route::get('ItemsTransferEntry/additemstransfercount/{id}/{store_id}/{count}', 'TransferController@additemstransfercount')->name('additemstransfercount');

  Route::get('ItemsTransferEntry/remove_itemtransfersitems/{id}/','TransferController@remove_itemtransfersitems')->name('remove_itemtransfersitems');

  Route::get('/ItemsTransferEntry/getstaffdata/get/{id}', 'TransferController@gettransferstaffdata') ;

  Route::post('ItemsTransferEntry/ItemsTransferCheckout/{store_id}','TransferController@itemtransfercheckout')->name('itemtransfercheckout');
  /*****Create End*****/

   /**Confirm Start**/
  Route::post('/ItemsTransferList/{id}/{name}/Confirm', 'TransferController@transfirmdetailconfirm')->name('transferdetail.confirm'); 
  // Route::get('/ItemsStreetList/{id}/{name}/RedoConfirm', 'StreetController@streetdetailredoconfirm')->name('streetdetail.redoconfirm');  
  /**Confirm End**/


  /*****Update Start***/
  Route::get('/ItemsTransferList/{transfer_number}/TransferUpdate', 'TransferController@edit')->name('transfer.edit'); 
  Route::post('/ItemsTransferList/{transfer_number}/TransferUpdate', 'TransferController@update')->name('transfer.update');

  Route::get('/ItemsTransferList/removeolditemssamdmtransfer/{itemname_id}/{id}/{transfer_id}','TransferController@removeolditemssamdmtransfer')->name('removeolditemssamdmtransfer');  
  /*****Update End***/


  Route::post('/ItemsTransferList/{id}/Delete', 'TransferController@destroy')->name('transfer.delete');

  /***********************Items Transfer End*************************************/

  /***********************Customer Name Start*************************************/
  Route::get('/CustomerNameList', 'CustomernameController@index')->name('customername.list');
  Route::get('/CustomerNameList/search/post', 'CustomernameController@searchpost')->name('customername.search');
  Route::post('/CustomerNameList/Entry','CustomernameController@store')->name('customername.store');
  Route::post('/CustomerNameList/{id}/Update','CustomernameController@update')->name('customername.update');
  Route::post('/CustomerNameList/{id}/delete', 'CustomernameController@destroy')->name('customername.delete');
  /***********************Customer Name End ************************************/

  /***********************Street Name Start*************************************/
  Route::get('/StreetNameList', 'StreetnameController@index')->name('streetname.list');
  Route::get('/StreetNameList/search/post', 'StreetnameController@searchpost')->name('streetname.search');
  Route::post('/StreetNameList/Entry','StreetnameController@store')->name('streetname.store');
  Route::post('/StreetNameList/{id}/Update','StreetnameController@update')->name('streetname.update');
  Route::post('/StreetNameList/{id}/delete', 'StreetnameController@destroy')->name('streetname.delete');
  /***********************Street Name End ************************************/
 
  /***********************History Start*************************************/
  Route::get('/HistoryList', 'HistoryController@index')->name('history.list');
  Route::post('/HistoryList/{year}/delete', 'HistoryController@destroyyear')->name('history.deleteyear');

  Route::get('/HistoryList/Month/{year}', 'HistoryController@monthlist')->name('history.monthlist');
  Route::post('/HistoryList/Month/{year}/{month}/delete', 'HistoryController@destroymonth')->name('history.deletemonth');
  Route::get('/HistoryList/Day/{year}/{month}', 'HistoryController@daylist')->name('history.daylist');
  Route::post('/HistoryList/Day/{year}/{month}/{day}/delete', 'HistoryController@destroyday')->name('history.deleteday');

  Route::get('/HistoryList/Date/{year}/{month}/{day}', 'HistoryController@datelist')->name('history.datelist');
  Route::get('/HistoryList/Date/{year}/{month}/{day}/search/post', 'HistoryController@searchdate')->name('history.searchdate');
   Route::post('/HistoryList/Date/{id}/delete', 'HistoryController@destroydata')->name('history.deletedata');
  /***********************History End*************************************/


});

 Route::get('/users/logout','Auth\LoginController@userLogout')->name('user.logout');
