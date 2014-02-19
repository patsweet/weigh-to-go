$(function() {
    // Updates for Bootstrap3
    // http://stackoverflow.com/a/18754780/1309020
    $.validator.setDefaults({
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            console.log(element.parent('.radio-inline').length);
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else if (element.parent('.radio-inline').length) {
                error.insertAfter(element.parent().parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    $("#registration_form").validate({
        rules: {
            zipcode: {
                required: true,
                digits: true,
                minlength: 5,
                maxlength: 5
            },
            age: {
                required: true,
                digits: true,
                maxlength: 3
            },
            gender: {
                required: true
            },
            ethnicity: {
                required: true
            },
            county: {
                required: true
            },
            email: {
                required: true
            },
            emailconfirm: {
                required: true,
                equalTo: "#emailfield"
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 25
            },
            password2: {
                required: true,
                equalTo: "#pwd"
            },
            height: {
                required: true,
                digits: true,
                rangelength: [2,3]
            },
            weight: {
                required: true,
                number: true,
                rangelength: [2,6]
            },
            "contest-rules": {
                required: true
            },
            q1: {
                required: true
            },
            q2a: {
                required: true
            },
            q2b: {
                required: {
                    depends: function(e) {
                        return $("input[name=q2a]:checked").val() == "1";
                    }
                }
            },
            q3: {
                required: true
            },
            q4: {
                required: true
            },
            q5: {
                required: true
            }
        }
    });

    if ($("input[name=q2a]:checked").val() == "1") {
        $("input[name=q2b]").closest(".form-group").show();
    }

    $("input[name=q2a]").change(function() {
        if ($(this).val() == "1") {
            $("input[name=q2b]").closest(".form-group").show();
        } else {
            $("input[name=q2b]").closest(".form-group").hide();
        }
    });

    $(document).on("change keyup mouseup", "input[name=weight], input[name=height]", function() {
        var height = +$("input[name=height]").val();
        var weight = parseFloat($("input[name=weight]").val(), 2);
        if (height > 0 && weight > 0) {
            var bmi = Math.round( ( (weight * 703) / Math.pow(height,2) ) * 100 ) / 100;
            $("input[name=bmi]").val(bmi);
        }
    });

    $("#contest-rules").prop("checked", false);
    $("#submit-button").click(function(e) {
        e.preventDefault();
        return false;
    });

    $("#contest-rules").change(function() {
        if ( $(this).is(":checked") ) {
            $("#submit-button")
                .removeClass("disabled")
                .click(function() {
                    $(this).closest("form").submit();
                });
        } else {
            $("#submit-button")
                .addClass("disabled")
                .unbind('click')
                .click(function(e){
                    e.preventDefault();
                    return false;
                });
        }
    });
});