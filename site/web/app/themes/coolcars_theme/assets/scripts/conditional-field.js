var FormStuff = {

  init: function() {
    this.applyConditionalRequired();
    this.bindUIActions();
  },

  bindUIActions: function() {
    jQuery("input[type='radio'], input[type='checkbox']").on("change", this.applyConditionalRequired);
  },

  applyConditionalRequired: function() {

    jQuery(".require-if-active").each(function() {
      var el = jQuery(this);
      if (jQuery(el.data("require-pair")).is(":checked")) {
        el.prop("required", true);
      } else {
        el.prop("required", false);
      }
    });

  }

};

FormStuff.init();
