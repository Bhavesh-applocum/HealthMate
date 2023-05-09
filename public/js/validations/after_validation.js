
$(document).ready(function () {
    $(document).on("input change", ".has-error input, .has-error select", function () {
        let isValidated = validated_form.element($(this));
        if (isValidated) {
            $(this).closest(".input-group").removeClass("has-error");
        }
    });
});
