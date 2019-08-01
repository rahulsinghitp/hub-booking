// Global Variable
var BasePath = 'http://192.168.0.81/hub-booking/source/';

function changeSelectedPerson() {
    var selectedPerson = $('#person-select').val();
    $('.selected-person').text(selectedPerson+' Person');
}

function changeSelectedTime() {
    var selectedTime = $('#time-slot-select').val();
    var TimeSlotOptions = [];
    $.each($('#time-slot-select option'), function() {
        var slot = $(this).val();
        var time = $(this).html();
        TimeSlotOptions[slot] = time;
    });

    // Set The Time .
    $('.selected-time').text(TimeSlotOptions[selectedTime]);
}

/**
 * DatePicker Script
 */
$(function() {
    $("#datepicker").datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "D, d M, yy",
      onSelect: function(dateText) {
        changeTimeSlotOptions(dateText);
      }
    });
    $("#datepicker").datepicker('setDate', 'today');
    var defaultDate = $('#datepicker').val();
    changeTimeSlotOptions(defaultDate);
});

// When Date Picker is changed
function changeTimeSlotOptions(dateText) {

    // Hide the Time SLots which are already booked
    $.ajax({
        url: BasePath+'api/hub-booking-list.php',
        type: 'post',
        dataType: 'json',
        data: {booking_date: dateText},
        success: function(result) {
            var bookedSlots = [];
            $.each(result, function(key, val) {
                bookedSlots.push(val.custom_hub_booking_time);
            });

            // Loop through time slot select options
            $.each($('#time-slot-select option'), function() {
                var slot = $(this).val();
                $(this).show();
                if (bookedSlots.indexOf(slot) != -1) {
                    $(this).hide();
                }
            });
        }
    });
}

$('.slider').slick({
  dots: true,
  infinite: false,
  arrows    : false,
  speed: 300,
  slidesToShow: 3,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: true,
        dots: true
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});

$('input:checkbox').change(function(){
  if ($(this).is(':checked')) {
    $(this).parent().addClass('selected');
	}
  else {
		$(this).parent().removeClass('selected');
	}
});

/**
 * First Form form Submit
 */
$("#hub-booking-step-1").submit(function(event) {
    var ReverseTimeSlots = [];
    $.each($('#time-slot-select option'), function() {
        var slot = $(this).val();
        var time = $(this).html();
        ReverseTimeSlots[time] = slot;
    });

    var selectedTime = $(".selected-time").html();
    var SelectedTimeInGMT = ReverseTimeSlots[selectedTime];
    var selectedDate = $("#datepicker").val();
    var selectedPerson = $('.selected-person').html().replace(/[^\d.]/g, '' );
		var selectedEquipments = [];
		var stopForm = false;
    $.each($("input[name='rGroup']:checked"), function() {
				selectedEquipments.push($(this).val());
		});
    if (!($.isNumeric(selectedPerson))) {
			alert('Please select the No. of Person');
			stopForm = true;
		}

		if (SelectedTimeInGMT == undefined) {
			alert('Please select the Time Slot');
			stopForm = true;
		}

		if (stopForm == true) {
			event.preventDefault();
		}
		else {
			localStorage.setItem('selectedPerson', selectedPerson);
			localStorage.setItem('selectedTimeInGMT', SelectedTimeInGMT);
			localStorage.setItem('selectedDate', selectedDate);
			localStorage.setItem('selectedTime', selectedTime);
			localStorage.setItem('selectedEquipments', selectedEquipments);
		}
});

/**
 * Prevent user to type in date field
 */
$('#datepicker').keydown(function(e) {
	e.preventDefault();
	return false;
});

// Code for timer start of 5 minutes
function startTimer() {
	if ($('#time').length > 0) {
		var presentTime = $('#timer').html();
		var timeArray = presentTime.split(/[:]+/);
		var m = timeArray[0];
		var s = checkSecond((timeArray[1] - 1));
		if (s == 59) {
			m = m-1;
		}
		$('#timer').html(m + ":" + s);
		if (m != 0 || s != 0) {
			setTimeout(startTimer, 1000);
		}
		else {
			$('#book-the-hub').hide();
		}
	}
  $('.selected-date').val(localStorage.getItem('selectedDate'));
  $('.selected-person').val(localStorage.getItem('selectedPerson'));
  $('.selected-time').val(localStorage.getItem('selectedTime'));
}

function checkSecond(sec) {
  if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
  if (sec < 0) {sec = "59"};
  return sec;
}

/**
 * Booking form Submit
 */
$("#hub-booking-step-2").submit(function(event) {
    var firstname = $('#first-name').val();
    var lastname = $('#last-name').val();
    var email = $('#email').val();
    var PhoneNumber = $('#phone-number').val();
		var UserID = $('#user-id').val();

		// Create A New User
		if (UserID == 0) {
			$.ajax({
				url: BasePath+'api/create-new-user.php',
				type: 'post',
				dataType: 'json',
				data: {firstname: firstname, lastname: lastname, email: email, phone_number: PhoneNumber},
				success: function(data) {

					// If User Account is created successfully create a new Booking Entry
					if (data.success == 1) {
						createHubBookingEntry(data.user_id);
					}
				}
			});
		}
		else {
			createHubBookingEntry(UserID);
		}
});

/**
 * Create Hub Booking Entry
 */
function createHubBookingEntry(UserID) {
	var selectedDate = localStorage.getItem('selectedDate');
	var selectedTimeInGMT = localStorage.getItem('selectedTimeInGMT');
	var selectedPerson = localStorage.getItem('selectedPerson');
	var selectedEquipments = localStorage.getItem('selectedEquipments');
	var Purpose = $('#purpose').val();
	if (UserID == '') {
		UserID = $('#user-id').val();
	}
	var json_data = {date: selectedDate, eqiupment_ids: selectedEquipments, time_in_gmt: selectedTimeInGMT, user_id: UserID, purpose: Purpose, persons: selectedPerson};
	$.ajax({
		url: BasePath+'api/create-hub-booking-entry.php',
		type: 'post',
		dataType: 'json',
		data: json_data,
		success: function(data) {
			/**
			// Clear the Local Storage Item which are set on Step 1
			localStorage.setItem('selectedPerson', '');
			localStorage.setItem('selectedTimeInGMT', '');
			localStorage.setItem('selectedDate', '');
			localStorage.setItem('selectedTime', '');
			localStorage.setItem('selectedEquipments', '');
			 */
		}
	});
}

// Checkbox of Equipment Selected
$('input:checkbox').change(function(){
	if($(this).is(':checked')) {
		$(this).parent().addClass('selected');
	}
 	else {
	  $(this).parent().removeClass('selected')
 	}
});


// For Transition
$(document).ready(function() {

	// Transition effect for navbar
	$(window).scroll(function() {

		// checks if window is scrolled more than 500px, adds/removes solid class
		if($(this).scrollTop() > 50) {
			$('.navbar').addClass('solid');
		}
		else {
			$('.navbar').removeClass('solid');
		}
	});
});

$('.slickslider-hero').slick({
  dots: false,
	arrows: false,
	autoplay: true,
 });

$(".dtp-picker-selector").hover(function(){
	$(this).toggleClass("clicked");
});