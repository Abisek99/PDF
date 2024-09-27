
// RL of the server 
var url = "http://localhost/assignment4/php/register.php";

$(function() {
  // On form submission
  $('#registration').submit(function(e) {
    e.preventDefault();
    send_request(); // Function to send the form data
  });
});

// Function to send the form data to the server
function send_request() {
  remove_msg();
  
  // POST request using AJAX
  $.ajax({
    url: url, 
    method: 'POST', // Use POST method
    data: $('#registration').serialize(), // Serialize form data
    dataType: 'json', // Expect JSON data in return
    success: function(data) {
      console.log(data.password);

      // Display the temporary password on the page
      $('#server_response').addClass('success');
      $('#server_response span').text('Temporary password: ' + data.password);
    },
    error: function(jqXHR) {
      // Handle errors by parsing the JSON response
      try {
        var $e = JSON.parse(jqXHR.responseText);

        console.log('Error from server: ' + $e.error);

        // Display the error message on the page
        $('#server_response').addClass('error');
        $('#server_response span').text('Error from server: ' + $e.error);
      } catch (error) {
        console.log('Could not parse JSON error message: ' + error);
      }
    }
  });
}

// Function to remove any existing success or error messages
function remove_msg() {
  var $server_response = $('#server_response');
  $server_response.removeClass('success error'); 
  $('#server_response span').text(''); 
}
