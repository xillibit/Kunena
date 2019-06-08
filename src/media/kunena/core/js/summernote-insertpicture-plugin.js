/**
 * 
 * copyright [year] [your Business Name and/or Your Name].
 * email: your@email.com
 * license: Your chosen license, or link to a license file.
 * 
 */
(function(factory) {
  /* global define */
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module.
    define(['jquery'], factory);
  } else if (typeof module === 'object' && module.exports) {
    // Node/CommonJS
    module.exports = factory(require('jquery'));
  } else {
    // Browser globals
    factory(window.jQuery);
  }
}(function($) {
  // Extends plugins for adding hello.
  //  - plugin is external module for customizing.
  $.extend($.summernote.plugins, {
    /**
     * @param {Object} context - context object has status of editor.
     */
    'insertpicture': function(context) {
    	var self      = this,

        // ui has renders to build ui elements
        // for e.g. you can create a button with 'ui.button'
         ui        = $.summernote.ui,
         $note     = context.layoutInfo.note,

         // contentEditable element
         $editor   = context.layoutInfo.editor,
         $editable = context.layoutInfo.editable,
         $toolbar  = context.layoutInfo.toolbar,
         
         // options holds the Options Information from Summernote and what we extended above.
         options   = context.options,
         
         // lang holds the Language Information from Summernote and what we extended above.
         lang      = options.langInfo;

      // add hello button
      context.memo('button.insertpicture', function() {
        // create button
        var button = ui.button({
          contents: '<i class="fa fa-child"/> Hello',
          tooltip: 'hello',
          click: function() {
        	  context.invoke('examplePlugin.show');
          },
        });

        // create jQuery object from button instance.
        return button.render();
      });

      // This events will be attached when editor is initialized.
      this.events = {
        // This will be called after modules are initialized.
        'summernote.init': function(we, e) {
          console.log('summernote initialized', we, e);
        },
        // This will be called when user releases a key on editable.
        'summernote.keyup': function(we, e) {
          console.log('summernote keyup', we, e);
        },
      };

      // This method will be called when editor is initialized by $('..').summernote();
      // You can create elements for plugin
      this.initialize = function() {
    	  var $container = options.dialogsInBody ? $(document.body) : $editor;

          // Build the Body HTML of the Dialog.
          var body = '<div class="form-group">' +
                     '</div>';

          // Build the Footer HTML of the Dialog.
          var footer = '<button href="#" class="btn btn-primary note-examplePlugin-btn">OK</button>';
          
          this.$dialog = ui.dialog({

              // Set the title for the Dialog. Note: We don't need to build the markup for the Modal
              // Header, we only need to set the Title.
              title: 'title',

              // Set the Body of the Dialog.
              body: body,

              // Set the Footer of the Dialog.
              footer: footer
              
            // This adds the Modal to the DOM.
            }).render().appendTo($container);
      };
      
      

      // This methods will be called when editor is destroyed by $('..').summernote('destroy');
      // You should remove elements on `initialize`.
      this.destroy = function() {
    	  ui.hideDialog(this.$dialog);
          this.$dialog.remove();
      };
      this.bindEnterKey = function ($input, $btn) {
          $input.on('keypress', function (event) {
            if (event.keyCode === 13) $btn.trigger('click');
          });
        };
        this.bindLabels = function () {
          self.$dialog.find('.form-control:first').focus().select();
          self.$dialog.find('label').on('click', function () {
            $(this).parent().find('.form-control:first').focus();
          });
        };
      this.show = function () {
          var $img = $($editable.data('target'));
          var editorInfo = {

          };
          this.showexamplePluginDialog(editorInfo).then(function (editorInfo) {
            ui.hideDialog(self.$dialog);
            $note.val(context.invoke('code'));
            $note.change();
          });
        };
        this.showexamplePluginDialog = function(editorInfo) {
          return $.Deferred(function (deferred) {
            ui.onDialogShown(self.$dialog, function () {
              context.triggerEvent('dialog.shown');
              $editBtn.click(function (e) {
                e.preventDefault();
                deferred.resolve({

                });
              });
              self.bindEnterKey($editBtn);
              self.bindLabels();
            });
            ui.onDialogHidden(self.$dialog, function () {
              $editBtn.off('click');
              if (deferred.state() === 'pending') deferred.reject();
            });
            ui.showDialog(self.$dialog);
          });
        };
    },
  });
}));