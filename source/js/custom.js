// Global Variable
var BasePath = 'http://localhost/hub-booking/source/';

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

$(function() {
    $( "#datepicker" ).datepicker({
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
        url: BasePath+'hub_booking_list.php',
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
    speed: 300,
    slidesToShow: 5,
    slidesToScroll: 1,
    responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 5,
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
    ]
});

$('input:checkbox').change(function(){
    if($(this).is(':checked'))
       $(this).parent().addClass('selected');
    else
       $(this).parent().removeClass('selected');
});

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
    $.each($("input[name='rGroup']:checked"), function() {
        selectedEquipments.push($(this).val());
    });
    if (!($.isNumeric(selectedPerson)) || SelectedTimeInGMT == 'undefined') {
        event.preventDefault();
    }
});
