function isNumber(n) {
    // Function from StackOverflow http://stackoverflow.com/a/1830844/1309020
    // Will correctly parse variables of any type and will return true or false
    // if it is or isn't a number.
    return !isNaN(parseFloat(n)) && isFinite(n);
}
$(function() {
    function checkForm() {
        var $error_message = $("<label class='error_message' style='color:red;font-weight:bold;'>This field is required.</label>");
        var errors_count = 0;
        $(".error_message").remove();
        var $form = $("#survey_form");
        // Check first question.
        var $q1 = $form.find("input[name=q1]:checked");
        if (!$q1.val()) {
            $error_message.clone().insertAfter($("#q1"));
            errors_count += 1;
        }
        // Check if second question is submitted.
        var $q2 = $form.find("input[name=q2]:checked");
        if (!$q2.val()) {
            $error_message.clone().insertAfter($("#q2"));
            errors_count += 1;
        }

        // Check the website feedback questions.
        var $website_rating = $form.find("select[name=websiteRating] option:selected");
        if (!$website_rating.val()) {
            $error_message.clone().insertAfter($("#website_rating"));
            errors_count += 1;
        }
        var $website_comments = $form.find("textarea[name=websiteComments]");
        if (!$website_comments.val()) {
            $error_message.clone().insertBefore($website_comments);
            errors_count += 1;
        }
        var $website_leaderboard = $form.find("textarea[name=websiteLeaderboard]");
        if (!$website_leaderboard.val()) {
            $error_message.clone().insertBefore($website_comments);
            errors_count += 1;
        }
        var $website_browser = $form.find("select[name=websiteBrowser] option:selected");
        if (!$website_browser.val()) {
            $error_message.clone().insertBefore($("#website_browser"));
            errors_count += 1;
        }

        // Depending on the answer to Question 2, we'll validate one
        // of two groups of questions.
        if (+$q2.val() === 0) {
            // Person didn't complete the program, so we need an answer
            // as to why that happened.
            var $not_complete = $("textarea[name=notcomplete]");
            if (!$not_complete.val()) {
                $error_message.clone().insertBefore($not_complete);
                errors_count += 1;
            }
        } else if (+$q2.val() === 1) {
            var $q3 = $form.find("input[name=q3]:checked");
            if (!$q3.val()) {
                $error_message.clone().insertAfter($("#q3"));
                errors_count += 1;
            }
            var $q4 = $form.find("input[name=q4]:checked");
            if (!$q4.val()) {
                $error_message.clone().insertAfter($("#q4"));
                errors_count += 1;
            }
            var $q5 = $form.find("input[name=q5]:checked");
            if (!$q5.val()) {
                $error_message.clone().insertAfter($("#q5"));
                errors_count += 1;
            }
            var $q6 = $form.find("input[name=q6]:checked");
            if (!$q6.val()) {
                $error_message.clone().insertAfter($("#q6"));
                errors_count += 1;
            }
            var $q7 = $form.find("select[name=q7] option:selected");
            if (!$q7.val()) {
                $error_message.clone().insertAfter($("#q7"));
                errors_count += 1;
            }
            var $q8 = $form.find("input[name=q8]");
            if (!$q8.val()) {
                $error_message.clone().insertAfter($("#q8"));
                errors_count += 1;
            } else if (!isNumber($q8.val())) {
                // If they have entered something into the weight field, let's make
                // sure it's a number.
                $("<label class='error_message' style='color:red;font-weight:bold;'>Please enter a valid number.</label>").insertAfter($("#q8"));
                errors_count += 1;
            }
        }
        if (errors_count > 0) {
            $form.find("fieldset").before("<h4 class='error_message' style='color:orange;font-weight:bold;'>Please fix the following error(s).</h4>");
            return false;
        }
        return true;
    }

    $("#submit-button").click(function(e) {
        var form_clean = checkForm();
        if (form_clean) {
            $("#survey_form").submit();
            return true;
        }
        e.preventDefault();
        return false;
    });
    $("#survey_form input[name=q2]").change(function() {
        var response = +$(this).val();
        if (response === 1) {
            $("#notcomplete").hide();
            $("#didcomplete").show();
        } else {
            $("#notcomplete").show();
            $("#didcomplete").hide();
        }
    });
});