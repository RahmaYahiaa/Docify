<?php

return [
    'register_doctor' => 'you are pending wait until admin approval',
    'invalid_otp_or_expired' => 'invalid otp or expired',
    'password_reset_successfully' => 'password reset successfully.',
    'OTP_sent_to_your_email' => ' OTP sent to your email',
    'Invalid_Google_token' => 'Invalid Google token',
    'Please_choose_your_role' => 'Please choose your role',
    'Prescription_generated_successfully' => 'Prescription generated successfully',
    'role_created_successfully' => 'Role created successfully.',
    'role_updated_successfully' => 'Role updated successfully.',
    'role_deleted_successfully' => 'Role deleted successfully.',
    'role_has_associated_users' => 'This role has associated users.',
    'admin_role_cannot_be_deleted' => 'Admin role cannot be deleted.',
    'country_created_successfully' => 'Country created successfully',
    'Upload_your_certificate' => 'Upload your certificate',
    'media_property_deleted_successfully' => 'Media property deleted successfully.',
    'permission_has_associated_roles' => 'The permission associated with roles.',
    'permission_has_associated_users' => 'The permission associated with users.',
    'permissions_deleted_successfully' => 'Permission deleted successfully.',
    'register_successfully' => 'register successfully',
    'profile_updated' => 'Profile updated successfully',
    'uploaded_successfully' => ' certificate uploaded successfully',

    //patient profile
    'medical_data_retrieved_successfully' => 'Medical data retrieved successfully',
    'medical_data_updated_successfully' => 'Medical data updated successfully',
    'medication_created_successfully' => 'medication created successfully',
    'medication_updated_successfully' => 'medication updated successfully',
    'medication_deleted_successfully' => 'medication deleted successfully',
    'healthCard_not_Found' => 'health card not found',

    //Appointment
    // Appointments — Patient
    'appointment_booked_successfully' => 'Your appointment has been booked successfully.',
    'appointment_cancelled_successfully' => 'Your appointment has been cancelled successfully.',
    'appointment_rescheduled_successfully' => 'Your appointment has been rescheduled successfully.',
    'appointment_initiated_successfully' => 'Appointment initiated. Please complete your payment within 10 minutes to confirm.',
    'appointments_list_retrieved_successfully' => 'Appointments retrieved successfully.',
    'appointment_details_retrieved_successfully' => 'Appointment details retrieved successfully.',

    // Appointments — Errors
    'appointment_slot_not_found_or_unavailable' => 'This time slot is no longer available. Please select a different time.',
    'appointment_slot_in_the_past' => 'This time slot has already passed. Please choose a future time.',
    'cannot_reschedule_finalized_appointment' => 'This appointment cannot be rescheduled because it has already been completed, cancelled, or marked as no-show.',
    'cannot_reschedule_within_24_hours' => 'Rescheduling is not available within 24 hours of your appointment. Please cancel instead.',
    'cannot_cancel_finalized_appointment' => 'This appointment cannot be cancelled because it has already been completed, cancelled, or marked as no-show.',
    'reschedule_doctor_mismatch' => 'You can only reschedule to a slot with the same doctor. To switch doctors, please cancel this appointment and book a new one.',
    'reschedule_type_mismatch' => 'You can only reschedule to the same consultation type (video or in-person). To change the type, please cancel and book a new appointment.',
    'invalid_consultation_type' => 'The selected slot does not match the consultation type. Please select a valid slot.',
    'unauthorized_action' => 'You are not authorized to perform this action.',

    // Doctors
    'doctor_not_found' => 'Doctor not found.',
    'requested_user_not_a_doctor' => 'The requested user is not a doctor.',
    'doctor_profile_retrieved_successfully' => 'Doctor profile retrieved successfully.',
    'doctors_list_retrieved_successfully' => 'Doctors list retrieved successfully.',
    'doctor_video_fee_not_set' => 'This doctor has not set a fee for video consultations yet.',

    // Availability — Doctor
    'doctor_availability_slots_retrieved_successfully' => 'Available slots retrieved successfully.',
    'doctor_availability_slots_created_successfully' => 'Your availability slots have been created successfully.',
    'doctor_availability_slots_blocked_successfully' => 'The selected time range has been blocked successfully.',
    'start_time_must_be_before_end_time' => 'Start time must be before end time.',
    'overlapping_slots' => 'The selected time range overlaps with an existing slot. Please choose a different time.',
    'no_slots_generated' => 'No slots could be generated. Please check your time range and duration.',
    'clinic_id_required_for_in_person_sessions' => 'A clinic must be selected for in-person consultation slots.',
    'no_clinic_assigned_to_profile' => 'No clinic is assigned to your profile. Please update your profile first.',
    'selected_clinic_not_assigned_to_profile' => 'The selected clinic is not assigned to your profile.',
    'cannot_block_slots_with_appointments' => 'Cannot block this time range — one or more slots have confirmed appointments.',
    'video_fee_not_set' => 'Please set your video consultation fee in your profile before creating video slots.',
    'in_person_fee_not_set' => 'Please set your in-person consultation fee in your profile before creating clinic slots.',
    'cannot_delete_booked_slot' => 'Cannot delete this slot because it has a confirmed appointment.',
    'cannot_delete_locked_slot' => 'Cannot delete this slot because it is currently locked for payment.',
    'doctor_availability_slot_deleted_successfully' => 'The selected availability slot has been deleted successfully.',

    // Appointments — Doctor
    'appointment_completed_successfully' => 'Appointment marked as completed successfully.',
    'appointment_marked_no_show_successfully' => 'Appointment marked as no-show successfully.',
    'appointment_already_finalized' => 'This appointment has already been completed, cancelled, or marked as no-show.',
    'cannot_mark_future_appointment_no_show' => 'Cannot mark a future appointment as no-show. The appointment time has not passed yet.',

    // Payment
    'payment_not_found' => 'Payment record not found.',
    'payment_not_refundable' => 'This payment cannot be refunded.',
    'payment_not_eligible_for_payout' => 'Payout could not be processed for this appointment. Please contact support.',

    'payment_confirmed_successfully' => 'Your payment has been verified and your appointment is confirmed.',
    'payment_failed' => 'Payment was unsuccessful. Please try again or use a different card.',
    'payment_pending' => 'We are waiting for the payment confirmation from the bank.',
    'payment_expired' => 'The payment window has expired. Please initiate a new booking.',

    //Video integration
    'video_session_not_applicable' => 'This appointment is not a video consultation.',
    'video_session_appointment_not_confirmed' => 'This appointment is not confirmed yet. Please complete your payment to access the video session.',
    'video_session_too_early' => 'You can join the video session starting :minutes minutes before the appointment time.',
    'video_session_window_closed' => 'The join window for this video session has closed. You can no longer join the session.',
    'video_session_token_generated_successfully' => 'Video session token generated successfully.',

    //Chat
    'message_sent_successfully' => 'Message sent successfully',
    'conversations_retrieved_successfully' => 'Conversations retrieved successfully',
    'conversation_messages_retrieved_successfully' => 'Conversation messages retrieved successfully',
    'messages_marked_as_read' => 'Messages marked as read',
    'typing_indicator_sent' => 'Typing indicator sent',
    'message_deleted_successfully' => 'Message deleted successfully',
    'conversation_initiated_successfully' => 'Conversation initiated successfully',
    'user_blocked_successfully'   => 'User blocked successfully.',
    'user_unblocked_successfully' => 'User unblocked successfully.',
    'you_are_not_part_of_this_conversation' => 'You are not part of this conversation.',
    'presence_updated' => 'Presence status updated successfully.',

    'message_does_not_belong_to_this_conversation' => 'Message does not belong to this conversation.',
    'you_cannot_block_yourself' => 'You cannot block yourself.',
    'you_can_only_delete_your_own_messages' => 'You can only delete your own messages.',
    'you_cannot_message_this_user' => 'You cannot message this user.',

    'not_found' => 'not found',
    'message_created_successfully' => 'message created successfully',
    'method_not_allowed' => 'method not allowed',
    'invalid_date_format' => 'invalid date format',
    'admin_role_cannot_be_updated' => 'admin role cannot be updated',
    'user_deleted_successfully' => 'user deleted successfully',
    'roles_assigned_successfully' => 'roles assigned successfully',

    'user_updated_successfully' => 'user updated successfully',
    'admin_cannot_be_deleted' => 'admin cannot be deleted',
    'permissions_assigned_successfully' => 'permissions assigned successfully',
    'roles_updated_successfully' => 'roles_updated_successfully',

    //notification
    'fcm_token_stored_successfully' => 'device token stored successfully',
    'notification_marked_as_read' => 'Notification marked as read',
    'all_notifications_marked_as_read' => 'all notifications marked as read',
    'notification_deleted_successfully' => 'notification deleted successfully',

    'assistant_added_successfully' => 'assistant added successfully',
    'appointment_status_updated' => 'appointment status updated',
    'payment_confirmed' => 'payment confirmed',

    //reviews
    'review_created_successfully' => 'review created successfully',
    'register_doctor_waiting_admin' => 'register doctor waiting admin approval',

    //Admin
    'doctor_rejected_successfully' => 'doctor rejected successfully',
    'doctor_accepted_successfully' => 'doctor accepted successfully',


    //specialty
    'specialization_created_successfully' => 'specialization created successfully',
    'specialization_updated_successfully' => 'specialization updated successfully',
    'specialization_disabled_successfully' => 'specialization disabled successfully',
    'specialization_activated_successfully' => 'specialization activated successfully',

    //user management
    'doctor_created_successfully' => 'doctor created successfully',
    'admin_created_successfully' => 'admin created successfully',
    'user_suspended' => 'user suspended',
    'user_activated_successfully' => 'user activated successfully',
    'Lab_report_uploaded_successfully' => 'Lab report uploaded successfully',
    'bank_added_successfully' => 'bank account added successfully',
    'measurement_saved' => 'measurement saved '
];
