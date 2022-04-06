<?php
require_once("autoloader.php");
require_once("Validation.php");

class Process
{
	public function __construct($data)
	{

		Common::setWebclient($data['webclient']);
		Common::setPlatform($data['platform']);
		$offline=array(
			'register', 'verifyemail', 'verifyotp',
			'logout', 'pendingTaskEmail', 'getIOSBuildNo');

		if(!in_array($data['module'], $offline))
		{
			$accessToken=$data['access_token'];
			$forceLogoff=true;
			if($accessToken!='')
			{
				$userID=Users::accessTokenValid($accessToken);
				Common::setUserID($userID);
				Common::setWorkspace($data['workspace']);
				if($userID!=null)
				{
					$forceLogoff=false;
				}
			}
			if($forceLogoff)
			{
				$returnData=array(
					'status' => 'error',
					'code' => 401,
					'message' => 'Unauthorized',
					'data' => [
					]
				);
				responseData($returnData);
				die;
			}
		}
		$returnData=array(
			'status' => 'error',
			'code' => 400,
			'message' => 'Bad Request',
			'data' => [
			]
		);
		switch($data['module'])
		{
			case 'register':
				if($data['req_method']=="POST")
					$returnData=Users::registerController($data['input_data']);
			break;

			case 'verifyemail':
				if($data['req_method']=="POST")
					$returnData=Users::verifyEmail($data['input_data']);
			break;

			case 'verifyotp':
				if($data['req_method']=="POST")
					$returnData=Users::verifyOTP($data['input_data']);
			break;

			case 'logout':
				$returnData=Users::logoutAccessToken($data['access_token']);
			break;

			case 'fabric':
				if($data['req_method']=="POST")
					$returnData=Fabric::createFabric($data['input_data']);
			break;

			case 'category':
				if($data['req_method']=="POST")
					$returnData=Category::createCategory($data['input_data']);
			break;

			case 'article':
				if($data['req_method']=="POST")
					$returnData=Article::createArticle($data['input_data']);
			break;

			case 'notifications':
				if($data['req_method']=="GET")
					$returnData=Notifications::getNotifications($data['input_data']);
			break;

			case 'orderEssentials':
				if($data['req_method']=="GET")
					$returnData=Orders::orderEssentials();
			break;

			case 'size':
				if($data['req_method']=="POST")
					$returnData=Size::createSize($data['input_data']);
			break;

			case 'color':
				if($data['req_method']=="POST")
					$returnData=Colors::createColor($data['input_data']);
			break;

			case 'readNotification':
				if($data['req_method']=="POST")
					$returnData=
					Notifications::readNotification($data['input_data']);
			break;

			case 'orderSKU':
				if($data['req_method']=="POST")
					$returnData=Orders::updateOrderSKU($data['input_data'], $data['data']);
				if($data['req_method']=="GET")
					$returnData=Orders::viewSKUDetails($data['input_data'], $data['data']);
			break;

			case 'allColorSize':
				if($data['req_method']=="GET")
					$returnData=Orders::sizeColorMaster();
			break;

			case 'getIOSBuildNo':
				if($data['req_method']=="GET")
					$returnData=array(
						'status' => 'success',
						'code' => 200,
						'message' => 'Current iOS Build Version',
						'data' => [
							'buildNo' => IOS_BUILD_NO
						]
					);
			break;

			case 'contact':
				if($data['req_method']=="GET")
					$returnData=Contacts::getContacts();
				if($data['req_method']=="POST")
					$returnData=Contacts::createContact($data['input_data']);
			break;

			case 'userInfo':
				if($data['req_method']=="GET")
					$returnData=Users::basicInfo();
			break;

			case 'orderByID':
				if($data['req_method']=="GET")
					$returnData=Orders::getOrderByID($data['data']);
			break;

			case 'updateAccomplished':
				if($data['req_method']=="POST")
					$returnData=
					Tasks::saveAccomplished($data['input_data']);
			break;

			case 'rescheduleDate':
				if($data['req_method']=="POST")
					$returnData=Tasks::rescheduleDate($data['input_data']);
			break;

			case 'saveTaskTemp':
				if($data['req_method']=="POST")
					$returnData=Tasks::saveTaskTemp($data['input_data']);
			break;

			case 'rescheduleHistory':
				if($data['req_method']=="POST")
					$returnData=Tasks::rescheduleHistory($data['input_data']);
			break;

			case 'updateOrderContact':
				if($data['req_method']=="POST")
					$returnData=
					Orders::updateOrderContacts(
						$data['input_data'], $data['data']);
			break;

			case 'pendingTasks':
				if($data['req_method']=="POST")
					$returnData=Reports::pendingTasks($data['input_data']);
			break;

			case 'order':
				if($data['req_method']=="GET")
					$returnData=Orders::getOrders($data['input_data']);
				if($data['req_method']=="POST")
					$returnData=Orders::createUpdateOrder($data['input_data']);
			break;

			case 'workspace':
				if($data['req_method']=="GET")
					$returnData=Workspace::getWorkspace();
			break;

			case 'addBuyer':
				if($data['req_method']=="POST")
				{
					$buyerData=$data['input_data'];
					$buyerData['partner_type']=1;
					$returnData=Partners::createPartner($buyerData);
				}
			break;

			case 'addPCU':
				if($data['req_method']=="POST")
				{
					$pcuData=$data['input_data'];
					$pcuData['partner_type']=2;
					$returnData=Partners::createPartner($pcuData);
				}
			break;

			case 'addFactory':
				if($data['req_method']=="POST")
				{
					$factoryData=$data['input_data'];
					$factoryData['partner_type']=3;
					$returnData=Partners::createPartner($factoryData);
				}
			break;

			case 'getTaskData':
				if($data['req_method']=="GET")
					$returnData=Tasks::getTasks($data['data']);
			break;

			case 'getTaskTempStruct':
				if($data['req_method']=="POST")
					$returnData=
					Tasks::tempFetchById(
						$data['input_data']);
			break;

			case 'updateTaskData':
				if($data['req_method']=="POST")
					$returnData=Orders::updateTasks(
						$data['input_data'], $data['data']);
			break;

			case 'setProductionData':
				if($data['req_method']=="POST")
					$returnData=
					Production::setProductionData($data['input_data']);
			break;

			case 'getProductionData':
				if($data['req_method']=="POST")
					$returnData=
					Production::getProductionData($data['input_data']);
			break;

			case 'pendingTaskEmail':
				if($data['req_method']=="GET")
					$returnData=Schedule::scheduleDailyTask();
			break;

			case 'partnerLists':
				if($data['req_method']=="GET")
					$returnData=Partners::getPartners();
			break;

			case 'reportFilters':
				if($data['req_method']=="GET")
					$returnData=Reports::reportFilters();
			break;

			case 'styleOrderList':
				if($data['req_method']=="POST")
					$returnData=Reports::styleOrderList($data['input_data']);
			break;

			case 'orderList':
				if($data['req_method']=="POST")
					$returnData=Reports::orderList($data['input_data']);
			break;

			case 'orderStatus':
				if($data['req_method']=="POST")
					$returnData=Reports::orderStatus($data['input_data']);
			break;

			case 'dashboardMetrics':
				if($data['req_method']=="GET")
					$returnData=Reports::dashboardMetrics();
			break;

			case 'partnerContacts':
				if($data['req_method']=="GET")
					$returnData=
					Contacts::getContactsByPartners($data['data']);
			break;

			case 'addPartnerContact':
				if($data['req_method']=="POST")
					$returnData=
					Contacts::createContact($data['input_data']);
			break;

			case 'updateContact':
				if($data['req_method']=="POST")
					$returnData=
					Contacts::updateContact($data['input_data']);
			break;

			case 'inactiveContact':
				if($data['req_method']=="POST")
					$returnData=
					Contacts::inactiveContact($data['input_data']);
			break;

			case 'activeContact':
				if($data['req_method']=="POST")
					$returnData=
					Contacts::activeContact($data['input_data']);
			break;

			case 'updateProdActuals':
				if($data['req_method']=="POST")
					$returnData=
					Production::productionActualUpdate($data['input_data']);
			break;

			case 'prodMetrics':
				if($data['req_method']=="POST")
					$returnData=
					Production::productionMetrics($data['input_data']);
			break;

			case 'orderStatusDetail':
				if($data['req_method']=="POST")
					$returnData=
					Reports::orderStatusDetail($data['input_data']);
			break;

			case 'editOrder':
				if($data['req_method']=="GET")
					$returnData=Orders::editOrder($data['data']);
			break;

			case '400':
				$returnData=array(
					'status' => 'error',
					'code' => 400,
					'message' => 'Bad Request',
					'data' => [
					]
				);
			break;

			case '404':
				$returnData=array(
					'status' => 'error',
					'code' => 404,
					'message' => 'Resource not found',
					'data' => [
					]
				);
			break;

			default:
				$returnData=array(
					'status' => 'error',
					'code' => 400,
					'message' => 'Bad Request',
					'data' => [
					]
				);
			break;
		}
		responseData($returnData, $data);
	}
}
?>