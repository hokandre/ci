// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable();
  $('#button-add-row').insertBefore('#dataTable');
});
