// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTableLogDashboard').DataTable({
    lengthMenu: [4, 8, 14, 20],
    dom: 'Bfrtip',
    buttons: [
      {
        extend: 'copy'
      }
    ]
  });
});

$(document).ready(function() {
  $('#dataTableOrdinance').DataTable({
    lengthMenu: [ [5, 15, 25, -1], [5, 15, 25, "All"] ],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: 0
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of City Ordinance - Selected',
          title: 'List of City Ordinance',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 7, 8]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of City Ordinance - Selected',
          title: 'List of City Ordinance',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 7, 8]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of City Ordinance - All',
          title: 'List of City Ordinance',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 7, 8]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of City Ordinance - All',
          title: 'List of City Ordinance',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 7, 8]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableCommittee').DataTable({
    lengthMenu: [ [5, 15, 25, -1], [5, 15, 25, "All"] ],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: 0
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Legislative Council Committee - Selected',
          title: 'List of Legislative Council Committee',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Legislative Council Committee - Selected',
          title: 'List of Legislative Council Committee',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Legislative Council Committee - All',
          title: 'List of Legislative Council Committee',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Legislative Council Committee - All',
          title: 'List of Legislative Council Committee',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableStaff').DataTable({
    lengthMenu: [3, 8, 14, 20],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: 0
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Staff - Selected',
          title: 'List of Staff',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 7, 8]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Staff - Selected',
          title: 'List of Staff',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 7, 8]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Staff - All',
          title: 'List of Staff',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                        // This is the right column
                        alignment: 'right',
                        text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                        fontSize: 8
                     }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                          alignment: 'right',
                          text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                          fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 7, 8]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Staff - All',
          title: 'List of Staff',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 7, 8]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableAdmin').DataTable({
    lengthMenu: [3, 8, 14, 20],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: 0
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Admin - Selected',
          title: 'List of Admin',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 6, 7]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Admin - Selected',
          title: 'List of Admin',
          exportOptions: {
            columns: [1, 2, 3, 4, 6, 7]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Admin - All',
          title: 'List of Admin',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 6, 7]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Admin - All',
          title: 'List of Admin',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 6, 7]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableSuperAdmin').DataTable({
    lengthMenu: [3, 8, 14, 20],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: 0
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Super Admin - Selected',
          title: 'List of Super Admin',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 6, 7]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Super Admin - Selected',
          title: 'List of Super Admin',
          exportOptions: {
            columns: [1, 2, 3, 4, 6, 7]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Super Admin - All',
          title: 'List of Super Admin',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 6, 7]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Super Admin - All',
          title: 'List of Super Admin',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 6, 7]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableLog').DataTable({
    lengthMenu: [4, 8, 14, 20],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: 0
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Logs - Selected',
          title: 'List of Logs',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Logs - Selected',
          title: 'List of Logs',
          exportOptions: {
            columns: [1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Logs - All',
          title: 'List of Logs',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Logs - All',
          title: 'List of Logs',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTablePendingAppointment').DataTable({
    lengthMenu: [4, 12, 20, 28],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: [0]
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Pending Appointment - Selected',
          title: 'List of Pending Appointment',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Pending Appointment - Selected',
          title: 'List of Pending Appointment',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Pending Appointment - All',
          title: 'List of Pending Appointment',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6, 7]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Pending Appointment - All',
          title: 'List of Pending Appointment',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6, 7]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableDeclinedAppointment').DataTable({
    lengthMenu: [4, 12, 20, 28],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: [0]
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Declined Appointment - Selected',
          title: 'List of Declined Appointment',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Declined Appointment - Selected',
          title: 'List of Declined Appointment',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Declined Appointment - All',
          title: 'List of Declined Appointment',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6, 7]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Declined Appointment - All',
          title: 'List of Declined Appointment',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6, 7]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableBookedAppointment').DataTable({
    lengthMenu: [4, 12, 20, 28],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: [0]
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Booked & Rescheduled Appointment - Selected',
          title: 'List of Booked & Rescheduled Appointment',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Booked & Rescheduled Appointment - Selected',
          title: 'List of Booked & Rescheduled Appointment',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Booked & Rescheduled Appointment - All',
          title: 'List of Booked & Rescheduled Appointment',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6, 7, 8]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Booked & Rescheduled Appointment - All',
          title: 'List of Booked & Rescheduled Appointment',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6, 7, 8]
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
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: [0]
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Finished Appointment - Selected',
          title: 'List of Finished Appointment',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Finished Appointment - Selected',
          title: 'List of Finished Appointment',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Finished Appointment - All',
          title: 'List of Finished Appointment',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6, 7, 8]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Finished Appointment - All',
          title: 'List of Finished Appointment',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6, 7, 8]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableNoShowAppointment').DataTable({
    lengthMenu: [4, 12, 20, 28],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: [0]
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of No-Show Appointment - Selected',
          title: 'List of No-Show Appointment',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of No-Show Appointment - Selected',
          title: 'List of No-Show Appointment',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of No-Show Appointment - All',
          title: 'List of No-Show Appointment',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6, 7]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of No-Show Appointment - All',
          title: 'List of No-Show Appointment',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6, 7]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableFeedbackAppointment').DataTable({
    lengthMenu: [4, 12, 20, 28],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: [0]
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Appointment Feedback - Selected',
          title: 'List of Appointment Feedback',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Appointment Feedback - Selected',
          title: 'List of Appointment Feedback',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Appointment Feedback - All',
          title: 'List of Appointment Feedback',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Appointment Feedback - All',
          title: 'List of Appointment Feedback',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTablePendingDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: [0]
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Pending Document Request - Selected',
          title: 'List of Pending Document Request',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Pending Document Request - Selected',
          title: 'List of Pending Document Request',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Pending Document Request - All',
          title: 'List of Pending Document Request',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Pending Document Request - All',
          title: 'List of Pending Document Request',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableDeclinedDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: [0]
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Declined Document Request - Selected',
          title: 'List of Declined Document Request',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Declined Document Request - Selected',
          title: 'List of Declined Document Request',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Declined Document Request - All',
          title: 'List of Declined Document Request',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Declined Document Request - All',
          title: 'List of Declined Document Request',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableApprovedDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: [0]
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Approved Document Request - Selected',
          title: 'List of Approved Document Request',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Approved Document Request - Selected',
          title: 'List of Approved Document Request',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Approved Document Request - All',
          title: 'List of Approved Document Request',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Approved Document Request - All',
          title: 'List of Approved Document Request',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableOnProcessDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: [0]
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of On Process Document Request - Selected',
          title: 'List of On Process Document Request',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of On Process Document Request - Selected',
          title: 'List of On Process Document Request',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of On Process Document Request - All',
          title: 'List of On Process Document Request',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of On Process Document Request - All',
          title: 'List of On Process Document Request',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableOnHoldDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: [0]
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of On Hold Document Request - Selected',
          title: 'List of On Hold Document Request',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of On Hold Document Request - Selected',
          title: 'List of On Hold Document Request',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of On Hold Document Request - All',
          title: 'List of On Hold Document Request',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of On Hold Document Request - All',
          title: 'List of On Hold Document Request',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableCancelledDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: [0]
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Cancelled Document Request - Selected',
          title: 'List of Cancelled Document Request',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Cancelled Document Request - Selected',
          title: 'List of Cancelled Document Request',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Cancelled Document Request - All',
          title: 'List of Cancelled Document Request',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Cancelled Document Request - All',
          title: 'List of Cancelled Document Request',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableToClaimDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: [0]
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of To Claim Document Request - Selected',
          title: 'List of To Claim Document Request',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of To Claim Document Request - Selected',
          title: 'List of To Claim Document Request',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of To Claim Document Request - All',
          title: 'List of To Claim Document Request',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of To Claim Document Request - All',
          title: 'List of To Claim Document Request',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableClaimedDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: [0]
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Claimed Document Request - Selected',
          title: 'List of Claimed Document Request',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Claimed Document Request - Selected',
          title: 'List of Claimed Document Request',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Claimed Document Request - All',
          title: 'List of Claimed Document Request',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6, 7]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Claimed Document Request - All',
          title: 'List of Claimed Document Request',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6, 7]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableUnclaimedDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: [0]
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Unclaimed Document Request - Selected',
          title: 'List of Unclaimed Document Request',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Unclaimed Document Request - Selected',
          title: 'List of Unclaimed Document Request',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Unclaimed Document Request - All',
          title: 'List of Unclaimed Document Request',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Unclaimed Document Request - All',
          title: 'List of Unclaimed Document Request',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableFeedbackDocumentRequest').DataTable({
    lengthMenu: [4, 12, 20, 28],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: [0]
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Document Request Feedback - Selected',
          title: 'List of Document Request Feedback',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Document Request Feedback - Selected',
          title: 'List of Document Request Feedback',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Document Request Feedback - All',
          title: 'List of Document Request Feedback',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Document Request Feedback - All',
          title: 'List of Document Request Feedback',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableUnansweredInquiry').DataTable({
    lengthMenu: [4, 12, 20, 28],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: [0]
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Unanswered Inquiry - Selected',
          title: 'List of Unanswered Inquiry',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Unanswered Inquiry - Selected',
          title: 'List of Unanswered Inquiry',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Unanswered Inquiry - Selected',
          title: 'List of Unanswered Inquiry',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Unanswered Inquiry - Selected',
          title: 'List of Unanswered Inquiry',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        }
      ]
  });
});

$(document).ready(function() {
  $('#dataTableAnsweredInquiry').DataTable({
    lengthMenu: [4, 12, 20, 28],
    columnDefs: [
      {
          orderable: false,
          render: DataTable.render.select(),
          targets: [0]
      }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    dom: 'Bfrtip',
      buttons: [
        {
          extend: 'pdfHtml5',
          text: 'PDF - Selected',
          filename: 'List of Answered Inquiry - Selected',
          title: 'List of Answered Inquiry',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - Selected',
          filename: 'List of Answered Inquiry - Selected',
          title: 'List of Answered Inquiry',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF - All',
          filename: 'List of Answered Inquiry - Selected',
          title: 'List of Answered Inquiry',
          customize: function (doc) {
            doc.content[0].text = doc.content[0].text.trim();
            doc.content[0].fontSize = 18;
            doc.content[0].bold = true;

            doc.styles.tableHeader.fontSize = 12;

            doc['header']=(function(page, pages) {
              return {
                  columns: [
                      {
                        alignment: 'left',
                        text: ['PedroAID'],
                        fontSize: 15,
                        bold: true,
                        color: '#35784F'
                      },
                      {
                          // This is the right column
                          alignment: 'right',
                          text: ['Generated on: ', { text: new Date().toLocaleDateString() }, ' - ', { text: new Date().toLocaleTimeString() }],
                          fontSize: 8
                      }
                  ],
                  margin: [10, 10]
              }
            });

            // Create a footer
            doc['footer']=(function(page, pages) {
                return {
                    columns: [
                        {
                            alignment: 'right',
                            text: ['Page ', { text: page.toString() },  ' of ', { text: pages.toString() }],
                            fontSize: 8
                        }
                    ],
                    margin: [10, 0]
                }
            });
          },
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          text: 'CSV - All',
          filename: 'List of Answered Inquiry - Selected',
          title: 'List of Answered Inquiry',
          exportOptions: {
              modifier: {
                  selected: null
              },
              columns: [1, 2, 3, 4, 5, 6]
          }
        }
      ]
  });
});