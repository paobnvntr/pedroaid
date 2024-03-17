<?php

use App\Http\Controllers\GenerateReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileUpdateController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\TrackerController;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\OrdinanceController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(LandingPageController::class)->group(function () {
    Route::get('/', 'home')->name('home');

    Route::get('city-ordinance/committee', 'displayCommittee')->name('displayCommittee');
    Route::get('city-ordinance/{committee_name}', 'displayYear')->name('displayYear');
    Route::get('city-ordinance/{committee_name}/{year}', 'displayOrdinance')->name('displayOrdinance');
    Route::get('search-ordinances', 'searchOrdinance')->name('searchOrdinance');

    Route::get('sangguniang-panlungsod', 'sangguniangPanlungsod')->name('sangguniangPanlungsod');
    Route::get('privacy-policy', 'privacyPolicy')->name('privacyPolicy');

    Route::get('appointment-form', 'appointmentForm')->name('appointmentForm');
    Route::get('checkDateAvailability', 'checkDateAvailability')->name('checkDateAvailability');
    Route::post('checkTimeAvailability', 'checkTimeAvailability')->name('checkTimeAvailability');
    Route::post('appointmentValidateForm', 'appointmentValidateForm')->name('appointmentValidateForm');
    Route::post('saveAppointment', 'saveAppointment')->name('saveAppointment');

    Route::get('inquiry-form', 'inquiryForm')->name('inquiryForm');
    Route::post('inquiry-form/validate', 'validateInquiryForm')->name('validateInquiryForm');
    Route::post('inquiry-form/saveInquiry', 'saveInquiry')->name('saveInquiry');

    Route::get('document-request-form', 'documentRequestForm')->name('documentRequestForm');
    Route::post('document-request-form/validate', 'validateDocumentRequestForm')->name('validateDocumentRequestForm');
    Route::post('document-request-form/saveDocumentRequest', 'saveDocumentRequest')->name('saveDocumentRequest');
});

Route::controller(WebhookController::class)->group(function () {
    Route::post('webhookAvailableDates', 'webhookAvailableDates')->name('webhookAvailableDates');
    Route::post('webhookCheckDateAvailability', 'webhookCheckDateAvailability')->name('webhookCheckDateAvailability');
    Route::post('webhookCheckTimeAvailability', 'webhookCheckTimeAvailability')->name('webhookCheckTimeAvailability');
    Route::post('webhookCityChecker', 'webhookCityChecker')->name('webhookCityChecker');
    Route::post('webhookCellphoneNumberChecker', 'webhookCellphoneNumberChecker')->name('webhookCellphoneNumberChecker');
    Route::post('webhookCreateAppoinment', 'webhookCreateAppoinment')->name('webhookCreateAppoinment');

    Route::post('webhookSearchOrdinanceByNumber', 'webhookSearchOrdinanceByNumber')->name('webhookSearchOrdinanceByNumber');
    Route::post('webhookSearchOrdinanceByTopic', 'webhookSearchOrdinanceByTopic')->name('webhookSearchOrdinanceByTopic');

    Route::post('webhookCreateInquiry', 'webhookCreateInquiry')->name('webhookCreateInquiry');

    Route::post('webhookTrackingIdChecker', 'webhookTrackingIdChecker')->name('webhookTrackingIdChecker');
    Route::post('webhookCheckStatus', 'webhookCheckStatus')->name('webhookCheckStatus');
});

Route::controller(TrackerController::class)->group(function () {
    Route::get('tracker', 'tracker')->name('tracker');

    Route::get('tracker/appointment-tracker', 'appointmentTracker')->name('appointmentTracker');
    Route::get('tracker/appointment-details/check-details', 'appointmentDetails')->name('appointmentDetails');
    Route::get('tracker/appointment-details', 'redirectAppointmentDetails')->name('redirectAppointmentDetails');
    Route::get('tracker/appointment-details/{appointment_id}', 'refreshAppointment')->name('refreshAppointment');
    Route::post('tracker/appointment-details/{appointment_id}', 'appointmentSendMessage')->name('appointmentSendMessage');

    Route::get('feedback-form/appointment/{appointment_id}', 'redirectFeedback')->name('redirectFeedback');
    Route::get('feedback-form/appointment', 'appointmentFeedbackForm')->name('appointmentFeedbackForm');
    Route::get('feedback-form/appointment/{appointment_id}/refresh', 'refreshFeedback')->name('refreshFeedback');
    
    Route::get('tracker/reschedule-appointment/{appointment_id}', 'rescheduleAppointment')->name('rescheduleAppointment');
    Route::get('tracker/reschedule-appointment/{appointment_id}/refresh', 'refreshReschedule')->name('refreshReschedule');
    Route::get('tracker/reschedule-appointment', 'appointmentRescheduleForm')->name('appointmentRescheduleForm');
    Route::post('tracker/reschedule-appointment/validate/{appointment_id}', 'validateReschedule')->name('validateReschedule');
    Route::post('tracker/reschedule-appointment/{appointment_id}', 'appointmentReschedule')->name('appointmentReschedule');
    
    Route::get('tracker/cancel-appointment/{appointment_id}', 'cancelAppointment')->name('cancelAppointment');

    Route::post('tracker/feedback-form/validate/{id}/{type}', 'validateFeedbackForm')->name('validateFeedbackForm');
    Route::post('tracker/feedback-form/{id}/{type}', 'sendFeedback')->name('sendFeedback');
    
    Route::get('tracker/edit-feedback-form/{id}', 'redirectEditFeedback')->name('redirectEditFeedback');
    Route::get('tracker/edit-feedback-form', 'appointmentEditFeedbackForm')->name('appointmentEditFeedbackForm');
    Route::get('tracker/edit-feedback-form/{id}/refresh', 'refreshEditFeedback')->name('refreshEditFeedback');
    Route::post('tracker/edit-feedback-form/validate/{id}', 'validateEditFeedbackForm')->name('validateEditFeedbackForm');
    Route::post('tracker/edit-feedback-form/{id}/{type}', 'editFeedback')->name('editFeedback');
    
    Route::get('tracker/inquiry-tracker', 'inquiryTracker')->name('inquiryTracker');
    Route::get('tracker/inquiry-details/check-details', 'inquiryDetails')->name('inquiryDetails');
    Route::get('tracker/inquiry-details', 'redirectInquiryDetails')->name('redirectInquiryDetails');
    Route::get('tracker/inquiry-details/{inquiry_id}', 'refreshInquiry')->name('refreshInquiry');
    Route::post('tracker/inquiry-details/{inquiry_id}', 'inquirySendMessage')->name('inquirySendMessage');

    Route::get('tracker/document-request-tracker', 'documentRequestTracker')->name('documentRequestTracker');
    Route::get('tracker/document-request-details/check-details', 'documentRequestDetails')->name('documentRequestDetails'); 
    Route::get('tracker/document-request-details', 'redirectDocumentRequestDetails')->name('redirectDocumentRequestDetails');
    Route::get('tracker/document-request-details/{documentRequest_id}', 'refreshDocumentRequest')->name('refreshDocumentRequest');
    Route::post('tracker/document-request-details/{documentRequest_id}', 'documentRequestSendMessage')->name('documentRequestSendMessage');
    Route::get('tracker/cancel-document-request/{documentRequest_id}', 'cancelDocumentRequest')->name('cancelDocumentRequest');

    Route::get('feedback-form/document-request/{documentRequest_id}', 'redirectDocumentRequestFeedback')->name('redirectDocumentRequestFeedback');
    Route::get('feedback-form/document-request', 'documentRequestFeedbackForm')->name('documentRequestFeedbackForm');
    Route::get('feedback-form/document-request/{documentRequest_id}/refresh', 'refreshDocumentRequestFeedback')->name('refreshDocumentRequestFeedback');

    Route::get('tracker/edit-feedback-form/document-request/{documentRequest_id}/redirect', 'redirectEditDocumentRequestFeedback')->name('redirectEditDocumentRequestFeedback');
    Route::get('tracker/edit-feedback-form/document-request/{documentRequest_id}', 'documentRequestEditFeedbackForm')->name('documentRequestEditFeedbackForm');
    Route::get('tracker/edit-feedback-form/document-request/{documentRequest_id}/refresh', 'refreshEditDocumentRequestFeedback')->name('refreshEditDocumentRequestFeedback');
    Route::post('tracker/edit-feedback-form/document-request/validate/{documentRequest_id}', 'validateEditDocumentRequestFeedbackForm')->name('validateEditDocumentRequestFeedbackForm');
    Route::post('tracker/edit-feedback-form/document-request/{documentRequest_id}/{type}', 'editDocumentRequestFeedback')->name('editDocumentRequestFeedback');
});


Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('loginAction', 'loginAction')->name('loginAction');
    Route::get('logout', 'logout')->middleware('auth')->name('logout');

    // Route::middleware('throttle:5,1')->group(function () {
    //     Route::post('loginAction', 'loginAction')->name('loginAction');
    // })
});

Route::middleware('auth')->group(function () {

    Route::controller(NotificationController::class)->group(function () {
        Route::post('mark-message-as-read/{notificationId}', 'markMessageAsRead')->name('markMessageAsRead');
        Route::post('mark-notification-as-read/{notificationId}', 'markNotificationAsRead')->name('markNotificationAsRead');
        Route::post('mark-all-messages-as-read', 'markAllMessagesAsRead')->name('markAllMessagesAsRead');
        Route::post('mark-all-notifications-as-read', 'markAllNotificationsAsRead')->name('markAllNotificationsAsRead');
        Route::get('show-all-messages', 'showAllMessages')->name('showAllMessages');
        Route::get('show-all-notifications', 'showAllNotifications')->name('showAllNotifications');
    });

    Route::controller(DashboardController::class)->prefix('dashboard')->group(function () {
        Route::get('', 'index')->name('dashboard');
        Route::get('getAppointmentFeedbackData', 'getAppointmentFeedbackData')->name('dashboard.getAppointmentFeedbackData');
        Route::get('getDocumentRequestFeedbackData', 'getDocumentRequestFeedbackData')->name('dashboard.getDocumentRequestFeedbackData');
    });

    Route::controller(ProfileUpdateController::class)->prefix('profile')->group(function () {
        Route::get('', 'profile')->name('profile');
        Route::post('validateProfileForm/{id}', 'validateProfileForm')->name('profile.validateProfileForm');
        Route::post('updateProfile', 'updateProfile')->name('profile.updateProfile');
    });

    Route::controller(StaffController::class)->prefix('staff')->group(function () {
        Route::get('', 'index')->name('staff');
        Route::get('addStaff', 'addStaff')->name('staff.addStaff');
        Route::post('validateAddStaffForm', 'validateAddStaffForm')->name('staff.validateAddStaffForm');
        Route::post('saveStaff', 'saveStaff')->name('staff.saveStaff');
        
        Route::get('editStaff/{id}', 'editStaff')->name('staff.editStaff');
        Route::post('validateEditStaffForm/{id}', 'validateEditStaffForm')->name('staff.validateEditStaffForm');
        Route::post('editStaff/{id}', 'updateStaff')->name('staff.updateStaff');

        Route::get('deleteStaff/{id}', 'deleteStaff')->name('staff.deleteStaff');
        Route::delete('destroyStaff/{id}', 'destroyStaff')->name('staff.destroyStaff');
    });

    Route::controller(AdminController::class)->prefix('admin')->group(function () {
        Route::get('', 'index')->name('admin');
        Route::get('addAdmin', 'addAdmin')->name('admin.addAdmin');
        Route::post('validateAddAdminForm', 'validateAddAdminForm')->name('admin.validateAddAdminForm');
        Route::post('saveAdmin', 'saveAdmin')->name('admin.saveAdmin');

        Route::get('editAdmin/{id}', 'editAdmin')->name('admin.editAdmin');
        Route::post('validateEditAdminForm/{id}', 'validateEditAdminForm')->name('admin.validateEditAdminForm');
        Route::post('editAdmin/{id}', 'updateAdmin')->name('admin.updateAdmin');

        Route::get('deleteAdmin/{id}', 'deleteAdmin')->name('admin.deleteAdmin');
        Route::delete('destroyAdmin/{id}', 'destroyAdmin')->name('admin.destroyAdmin');
    });

    Route::controller(SuperAdminController::class)->prefix('super-admin')->group(function () {
        Route::get('', 'index')->name('super-admin');
        Route::get('addSuperAdmin', 'addSuperAdmin')->name('super-admin.addSuperAdmin');
        Route::post('validateAddSuperAdminForm', 'validateAddSuperAdminForm')->name('super-admin.validateAddSuperAdminForm');
        Route::post('saveSuperAdmin', 'saveSuperAdmin')->name('super-admin.saveSuperAdmin');

        Route::get('editSuperAdmin/{id}', 'editSuperAdmin')->name('super-admin.editSuperAdmin');
        Route::post('validateEditSuperAdminForm/{id}', 'validateEditSuperAdminForm')->name('super-admin.validateEditSuperAdminForm');
        Route::post('editSuperAdmin/{id}', 'updateSuperAdmin')->name('super-admin.updateSuperAdmin');

        Route::get('deleteSuperAdmin/{id}', 'deleteSuperAdmin')->name('super-admin.deleteSuperAdmin');
        Route::delete('destroySuperAdmin/{id}', 'destroySuperAdmin')->name('super-admin.destroySuperAdmin');
    });

    Route::controller(LogController::class)->prefix('logs')->group(function () {
        Route::get('', 'logs')->name('logs');
    });

    Route::controller(OrdinanceController::class)->prefix('ordinance')->group(function () {
        Route::get('', 'index')->name('ordinance');
        Route::get('addOrdinance', 'addOrdinance')->name('ordinance.addOrdinance');
        Route::post('validateAddOrdinanceForm', 'validateAddOrdinanceForm')->name('ordinance.validateAddOrdinanceForm');
        Route::post('saveOrdinance', 'saveOrdinance')->name('ordinance.saveOrdinance');

        Route::get('editOrdinance/{id}', 'editOrdinance')->name('ordinance.editOrdinance');
        Route::post('validateEditOrdinanceForm/{id}', 'validateEditOrdinanceForm')->name('ordinance.validateEditOrdinanceForm');
        Route::post('editOrdinance/{id}', 'updateOrdinance')->name('ordinance.updateOrdinance');

        Route::get('deleteOrdinance/{id}', 'deleteOrdinance')->name('ordinance.deleteOrdinance');
        Route::delete('destroyOrdinance/{id}', 'destroyOrdinance')->name('ordinance.destroyOrdinance');
    });

    Route::controller(CommitteeController::class)->prefix('committee')->group(function () {
        Route::get('', 'index')->name('committee');
        Route::get('addCommittee', 'addCommittee')->name('committee.addCommittee');
        Route::post('validateAddCommitteeForm', 'validateAddCommitteeForm')->name('committee.validateAddCommitteeForm');
        Route::post('saveCommittee', 'saveCommittee')->name('committee.saveCommittee');

        Route::get('editCommittee/{id}', 'editCommittee')->name('committee.editCommittee');
        Route::post('validateEditCommitteeForm/{id}', 'validateEditCommitteeForm')->name('committee.validateEditCommitteeForm');
        Route::post('editCommittee/{id}', 'updateCommittee')->name('committee.updateCommittee');
        Route::get('deleteMember1/{id}', 'deleteMember1')->name('committee.deleteMember1');
        Route::get('deleteMember2/{id}', 'deleteMember2')->name('committee.deleteMember2');
        Route::get('deleteMember3/{id}', 'deleteMember3')->name('committee.deleteMember3');

        Route::get('deleteCommittee/{id}', 'deleteCommittee')->name('committee.deleteCommittee');
        Route::delete('destroyCommittee/{id}', 'destroyCommittee')->name('committee.destroyCommittee');
    });

    Route::controller(AppointmentController::class)->prefix('appointment')->group(function () {
        Route::get('', 'index')->name('appointment');
        Route::get('appointmentDetails/{id}', 'appointmentDetails')->name('appointment.appointmentDetails');
        Route::post('appointmentDetails/{id}', 'appointmentSendMessage')->name('appointment.appointmentSendMessage');

        Route::get('addAppointment', 'addAppointment')->name('appointment.addAppointment');
        Route::get('checkDateAvailability', 'checkDateAvailability')->name('appointment.checkDateAvailability');
        Route::post('checkTimeAvailability', 'checkTimeAvailability')->name('appointment.checkTimeAvailability');
        Route::post('validateForm', 'validateForm')->name('appointment.validateForm');
        Route::post('saveAppointment', 'saveAppointment')->name('appointment.saveAppointment');

        Route::get('editAppointment/{id}', 'editAppointment')->name('appointment.editAppointment');
        Route::post('validateEditForm/{id}', 'validateEditForm')->name('appointment.validateEditForm');
        Route::post('editAppointment/{id}', 'updateAppointment')->name('appointment.updateAppointment');
        
        Route::get('deleteAppointment/{id}', 'deleteAppointment')->name('appointment.deleteAppointment');
        Route::delete('destroyAppointment/{id}', 'destroyAppointment')->name('appointment.destroyAppointment');

        Route::get('pending-appointment', 'pendingAppointment')->name('appointment.pendingAppointment');
        Route::get('approveAppointment/{id}', 'approveAppointment')->name('appointment.approveAppointment');
        Route::get('declineAppointment/{id}', 'declineAppointment')->name('appointment.declineAppointment');

        Route::get('rescheduleAppointment/{id}', 'rescheduleAppointmentForm')->name('appointment.rescheduleAppointmentForm');
        Route::post('rescheduleAppointment/validate/{id}', 'validateReschedule')->name('appointment.validateReschedule');
        Route::post('rescheduleAppointment/{id}', 'appointmentReschedule')->name('appointment.appointmentReschedule');

        Route::get('finished-appointment', 'finishedAppointment')->name('appointment.finishedAppointment');
        Route::get('cancelAppointment/{id}', 'cancelAppointment')->name('appointment.cancelAppointment');
        Route::get('no-show-appointment/{id}', 'noShowAppointment')->name('appointment.noShowAppointment');
        Route::get('finishAppointment/{id}', 'finishAppointment')->name('appointment.finishAppointment');

        Route::get('appointment-feedback', 'appointmentFeedback')->name('appointment.appointmentFeedback');
        Route::get('feedbackForm/{id}', 'feedbackForm')->name('feedbackForm');
        Route::post('feedback-form/validate/{id}/{type}', 'validateFeedbackForm')->name('appointment.validateFeedbackForm');
        Route::post('feedback-form/{id}/{type}', 'saveFeedback')->name('appointment.saveFeedback');

        Route::get('feedbackEditForm/{id}', 'feedbackEditForm')->name('feedbackEditForm');
        Route::post('feedback-edit-form/validate/{id}/{type}', 'validateEditFeedbackForm')->name('appointment.validateEditFeedbackForm');
        Route::post('feedback-edit-form/{id}/{type}', 'saveEditFeedback')->name('appointment.saveEditFeedback');
        
        Route::get('deleteFeedback/{id}', 'deleteFeedback')->name('appointment.deleteFeedback');
        Route::delete('destroyFeedback/{id}', 'destroyFeedback')->name('appointment.destroyFeedback');
    });

    Route::controller(InquiryController::class)->prefix('inquiry')->group(function () {
        Route::get('', 'index')->name('inquiry');
    });

    Route::controller(DocumentRequestController::class)->prefix('document-request')->group(function () {
        Route::get('', 'index')->name('document-request');
        Route::get('pending-document-request', 'pendingDocumentRequest')->name('document-request.pendingDocumentRequest');
        Route::get('finished-document-request', 'finishedDocumentRequest')->name('document-request.finishedDocumentRequest');
        Route::get('document-request-details/{id}', 'documentRequestDetails')->name('document-request.documentRequestDetails');

        Route::get('approve-document-request/{id}', 'approveDocumentRequest')->name('document-request.approveDocumentRequest');
        Route::get('decline-document-request/{id}', 'declineDocumentRequest')->name('document-request.declineDocumentRequest');

        Route::post('document-request-details/{id}', 'documentRequestSendMessage')->name('document-request.documentRequestSendMessage');

        Route::get('process-document-request/{id}', 'processDocumentRequest')->name('document-request.processDocumentRequest');
        Route::get('hold-document-request/{id}', 'holdDocumentRequest')->name('document-request.holdDocumentRequest');
        Route::get('cancel-document-request/{id}', 'cancelDocumentRequest')->name('document-request.cancelDocumentRequest');
        Route::get('to-claim-document-request/{id}', 'toClaimDocumentRequest')->name('document-request.toClaimDocumentRequest');

        Route::get('claimed-document-request/{id}', 'claimedDocumentRequest')->name('document-request.claimedDocumentRequest');
        Route::get('unclaimed-document-request/{id}', 'unclaimedDocumentRequest')->name('document-request.unclaimedDocumentRequest');

        Route::get('add-document-request', 'addDocumentRequest')->name('document-request.addDocumentRequest');
        Route::post('validate-document-request-form', 'validateDocumentRequestForm')->name('document-request.validateDocumentRequestForm');
        Route::post('save-document-request', 'saveDocumentRequest')->name('document-request.saveDocumentRequest');

        Route::get('edit-document-request/{id}', 'editDocumentRequest')->name('document-request.editDocumentRequest');
        Route::post('validate-edit-same-document-request-form/{id}', 'validateEditSameDocumentRequestForm')->name('document-request.validateEditSameDocumentRequestForm');
        Route::post('validate-edit-new-document-request-form/{id}', 'validateEditNewDocumentRequestForm')->name('document-request.validateEditNewDocumentRequestForm');
        Route::post('edit-document-request/{id}', 'updateDocumentRequest')->name('document-request.updateDocumentRequest');

        Route::get('delete-document-request/{id}', 'deleteDocumentRequest')->name('document-request.deleteDocumentRequest');
        Route::delete('destroy-document-request/{id}', 'destroyDocumentRequest')->name('document-request.destroyDocumentRequest');

        Route::get('document-request-feedback', 'documentRequestFeedback')->name('document-request.documentRequestFeedback');

        Route::get('feedback-form/{id}', 'feedbackForm')->name('document-request.feedbackForm');
        Route::post('feedback-form/validate/{id}/{type}', 'validateFeedbackForm')->name('document-request.validateFeedbackForm');
        Route::post('feedback-form/{id}/{type}', 'saveFeedback')->name('document-request.saveFeedback');

        Route::get('feedback-edit-form/{id}', 'feedbackEditForm')->name('document-request.feedbackEditForm');
        Route::post('feedback-edit-form/validate/{id}/{type}', 'validateEditFeedbackForm')->name('document-request.validateEditFeedbackForm');
        Route::post('feedback-edit-form/{id}/{type}', 'saveEditFeedback')->name('document-request.saveEditFeedback');

        Route::get('delete-document-request-feedback/{id}', 'deleteFeedback')->name('document-request.deleteFeedback');
        Route::delete('destroy-document-request-feedback/{id}', 'destroyFeedback')->name('document-request.destroyFeedback');
    });
    
    Route::controller(InquiryController::class)->prefix('inquiry')->group(function () {
        Route::get('', 'index')->name('inquiry');
        Route::get('inquiry-details/{id}', 'inquiryDetails')->name('inquiry.inquiryDetails');

        Route::post('inquiry-details/{id}', 'inquirySendMessage')->name('inquiry.inquirySendMessage');

        Route::get('add-inquiry', 'addInquiry')->name('inquiry.addInquiry');
        Route::post('validate-inquiry-form', 'validateInquiryForm')->name('inquiry.validateInquiryForm');
        Route::post('save-inquiry', 'saveInquiry')->name('inquiry.saveInquiry');

        Route::get('edit-inquiry/{id}', 'editInquiry')->name('inquiry.editInquiry');
        Route::post('validate-edit-inquiry-form/{id}', 'validateEditInquiryForm')->name('inquiry.validateEditInquiryForm');
        Route::post('edit-inquiry/{id}', 'updateInquiry')->name('inquiry.updateInquiry');

        Route::get('delete-inquiry/{id}', 'deleteInquiry')->name('inquiry.deleteInquiry');
        Route::delete('destroy-inquiry/{id}', 'destroyInquiry')->name('inquiry.destroyInquiry');
    }); 

    Route::controller(GenerateReportController::class)->prefix('generate')->group(function () {
        Route::get('generate-appointment-report/{id}', 'generateAppointmentReport')->name('generate.appointment');
        Route::get('generate-document-request-report/{id}', 'generateDocumentRequestReport')->name('generate.document-request');
        Route::get('generate-inquiry-report/{id}', 'generateInquiryReport')->name('generate.inquiry');
    });    
});