// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTableOrdinance').DataTable({
    lengthMenu: [ [5, 15, 25, -1], [5, 15, 25, "All"] ],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of City Ordinance',
          title: 'List of City Ordinance',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 6, 7]
          }
        },
        {
          extend: 'excel',
          filename: 'List of City Ordinance',
          title: 'List of City Ordinance',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 6, 7]
          }
        },
        {
          extend: 'csv',
          filename: 'List of City Ordinance',
          title: 'List of City Ordinance',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 6, 7]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableCommittee').DataTable({
    lengthMenu: [ [5, 15, 25, -1], [5, 15, 25, "All"] ],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of Legislative Council Committee',
          title: 'List of Legislative Council Committee',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
          }
        },
        {
          extend: 'excel',
          filename: 'List of Legislative Council Committee',
          title: 'List of Legislative Council Committee',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
          }
        },
        {
          extend: 'csv',
          filename: 'List of Legislative Council Committee',
          title: 'List of Legislative Council Committee',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableStaff').DataTable({
    lengthMenu: [3, 8, 14, 20],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of Staff',
          title: 'List of Staff',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 6, 7]
          }
        },
        {
          extend: 'excel',
          filename: 'List of Staff',
          title: 'List of Staff',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 6, 7]
          }
        },
        {
          extend: 'csv',
          filename: 'List of Staff',
          title: 'List of Staff',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 6, 7]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableAdmin').DataTable({
    lengthMenu: [3, 8, 14, 20],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of Admin',
          title: 'List of Admin',
          exportOptions: {
            columns: [0, 1, 2, 3, 5, 6]
          }
        },
        {
          extend: 'excel',
          filename: 'List of Admin',
          title: 'List of Admin',
          exportOptions: {
            columns: [0, 1, 2, 3, 5, 6]
          }
        },
        {
          extend: 'csv',
          filename: 'List of Admin',
          title: 'List of Admin',
          exportOptions: {
            columns: [0, 1, 2, 3, 5, 6]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableSuperAdmin').DataTable({
    lengthMenu: [3, 8, 14, 20],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of Super Admin',
          title: 'List of Super Admin',
          exportOptions: {
            columns: [0, 1, 2, 3, 5, 6]
          }
        },
        {
          extend: 'excel',
          filename: 'List of Super Admin',
          title: 'List of Super Admin',
          exportOptions: {
            columns: [0, 1, 2, 3, 5, 6]
          }
        },
        {
          extend: 'csv',
          filename: 'List of Super Admin',
          title: 'List of Super Admin',
          exportOptions: {
            columns: [0, 1, 2, 3, 5, 6]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableLog').DataTable({
    lengthMenu: [4, 12, 20, 28],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of Logs',
          title: 'List of Logs'
        },
        {
          extend: 'excel',
          filename: 'List of Logs',
          title: 'List of Logs'
        },
        {
          extend: 'csv',
          filename: 'List of Logs',
          title: 'List of Logs'
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTablePendingAppointment').DataTable({
    lengthMenu: [4, 12, 20, 28],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of Pending Appointment',
          title: 'List of Pending Appointment',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'excel',
          filename: 'List of Pending Appointment',
          title: 'List of Pending Appointment',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          filename: 'List of Pending Appointment',
          title: 'List of Pending Appointment',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableDeclinedAppointment').DataTable({
    lengthMenu: [4, 12, 20, 28],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of Declined Appointment',
          title: 'List of Declined Appointment',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'excel',
          filename: 'List of Declined Appointment',
          title: 'List of Declined Appointment',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          filename: 'List of Declined Appointment',
          title: 'List of Declined Appointment',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableBookedAppointment').DataTable({
    lengthMenu: [4, 12, 20, 28],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of Booked & Rescheduled Appointment',
          title: 'List of Booked & Rescheduled Appointment',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7]
          }
        },
        {
          extend: 'excel',
          filename: 'List of Booked & Rescheduled Appointment',
          title: 'List of Booked & Rescheduled Appointment',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7]
          }
        },
        {
          extend: 'csv',
          filename: 'List of Booked & Rescheduled Appointment',
          title: 'List of Booked & Rescheduled Appointment',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableCancelledAppointment').DataTable({
    lengthMenu: [4, 12, 20, 28],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of Cancelled Appointment',
          title: 'List of Cancelled Appointment',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'excel',
          filename: 'List of Cancelled Appointment',
          title: 'List of Cancelled Appointment',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          filename: 'List of Cancelled Appointment',
          title: 'List of Cancelled Appointment',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableFinishedAppointment').DataTable({
    lengthMenu: [4, 12, 20, 28],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of Finished Appointment',
          title: 'List of Finished Appointment',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7]
          }
        },
        {
          extend: 'excel',
          filename: 'List of Finished Appointment',
          title: 'List of Finished Appointment',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7]
          }
        },
        {
          extend: 'csv',
          filename: 'List of Finished Appointment',
          title: 'List of Finished Appointment',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableNoShowAppointment').DataTable({
    lengthMenu: [4, 12, 20, 28],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of No-Show Appointment',
          title: 'List of No-Show Appointment',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'excel',
          filename: 'List of No-Show Appointment',
          title: 'List of No-Show Appointment',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          filename: 'List of No-Show Appointment',
          title: 'List of No-Show Appointment',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableFeedbackAppointment').DataTable({
    lengthMenu: [4, 12, 20, 28],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of Appointment Feedback',
          title: 'List of Appointment Feedback',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'excel',
          filename: 'List of Appointment Feedback',
          title: 'List of Appointment Feedback',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'csv',
          filename: 'List of Appointment Feedback',
          title: 'List of Appointment Feedback',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTablePendingDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of Pending Document Request',
          title: 'List of Pending Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'excel',
          filename: 'List of Pending Document Request',
          title: 'List of Pending Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'csv',
          filename: 'List of Pending Document Request',
          title: 'List of Pending Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableDeclinedDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of Declined Document Request',
          title: 'List of Declined Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'excel',
          filename: 'List of Declined Document Request',
          title: 'List of Declined Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'csv',
          filename: 'List of Declined Document Request',
          title: 'List of Declined Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableApprovedDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of Approved Document Request',
          title: 'List of Approved Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'excel',
          filename: 'List of Approved Document Request',
          title: 'List of Approved Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'csv',
          filename: 'List of Approved Document Request',
          title: 'List of Approved Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableOnProcessDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of On Process Document Request',
          title: 'List of On Process Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'excel',
          filename: 'List of On Process Document Request',
          title: 'List of On Process Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'csv',
          filename: 'List of On Process Document Request',
          title: 'List of On Process Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableOnHoldDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of On Hold Document Request',
          title: 'List of On Hold Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'excel',
          filename: 'List of On Hold Document Request',
          title: 'List of On Hold Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'csv',
          filename: 'List of On Hold Document Request',
          title: 'List of On Hold Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableCancelledDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of Cancelled Document Request',
          title: 'List of Cancelled Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'excel',
          filename: 'List of Cancelled Document Request',
          title: 'List of Cancelled Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'csv',
          filename: 'List of Cancelled Document Request',
          title: 'List of Cancelled Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableToClaimDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of To Claim Document Request',
          title: 'List of To Claim Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'excel',
          filename: 'List of To Claim Document Request',
          title: 'List of To Claim Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'csv',
          filename: 'List of To Claim Document Request',
          title: 'List of To Claim Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableClaimedDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of Claimed Document Request',
          title: 'List of Claimed Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'excel',
          filename: 'List of Claimed Document Request',
          title: 'List of Claimed Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          filename: 'List of Claimed Document Request',
          title: 'List of Claimed Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableUnclaimedDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdf',
          filename: 'List of Unclaimed Document Request',
          title: 'List of Unclaimed Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'excel',
          filename: 'List of Unclaimed Document Request',
          title: 'List of Unclaimed Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'csv',
          filename: 'List of Unclaimed Document Request',
          title: 'List of Unclaimed Document Request',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableOnProcess').DataTable({
    lengthMenu: [4, 12, 20, 28]
  });
});

$(document).ready(function() {
  $('#dataTableToClaim').DataTable({
    lengthMenu: [4, 12, 20, 28]
  });
});