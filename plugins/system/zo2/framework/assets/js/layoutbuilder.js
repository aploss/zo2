/**
 * Zo2 Layout builder
 * @param {type} w
 * @param {type} z
 * @param {type} $
 * @returns {undefined}
 */
(function (w, z, $) {

    /* Layout builder local */
    var _layoutbuilder = {
        /* Element selectors */
        _elements: {
            layoutBuilderContainer: '#zo2-layoutbuilder-container > #zo2-layoutbuilder',
            childrenContainer: '.children-container',
            sortableConnect: '.connectedSortable',
            sortableRow: '.sortable-row',
            joomlaTooltip: '.tooltip',
            parentContainer: '.parent-container',
            controlGroup: '.control-group',
            rowName: '.row-name',
            rowSize: '.row-size',
            rowWidth: '.row-width',
            emptyParentRow: '#zo2-layoutbuilder-container > #zo2-empty-parent-row',
            emptyChildRow: '#zo2-layoutbuilder-container > #zo2-empty-child-row',
            /* Modals */
            rowSetting: '#zo2-layoutbuilder-container > #zo2-row-setting',
            rowDelete: '#zo2-layoutbuilder-container > #zo2-delete-confirm',
            jsonField: '#zo2-layoutbuilder-container > #zo2-layoutbuilder-json',
            /* Settings */
            settingName: '#zo2-setting-name',
            settingJdoc: '#zo2-setting-jdoc',
            settingPosition: '#zo2-setting-position',
            settingStyle: '#zo2-setting-style',
            settingOffset: '#zo2-setting-offset',
            settingCss: '#zo2-setting-css',
            /* Visibility */
            visibleMobile: '#zo2-enable-responsive-mobile',
            visibleTable: '#zo2-enable-responsive-tablet',
            visibleDesktop: '#zo2-enable-responsive-desktop',
            visibleLargeDesktop: '#zo2-enable-responsive-largedesktop'
        },
        /* Layout JSON buffer */
        layoutJson: null,
        /* Current editing element */
        editingElement: null,
        /**
         * Init function
         * @returns {undefined}
         */
        _init: function () {
            var _self = this;
            /* Move child rows */
            $(_self._elements.childrenContainer).sortable({
                handle: '#move-row',
                placeholder: "sortable-hightligth",
                start: function (event, ui) {
                    ui.placeholder.height(ui.helper.outerHeight());
                    ui.placeholder.addClass(ui.item.attr('class'));
                },
                stop: function (event, ui) {
                    ui.placeholder.removeClass(ui.item.attr('class'));
                },
                connectWith: _self._elements.sortableConnect
            }).disableSelection();
            /* Sort able parent row */
            $(_self._elements.layoutBuilderContainer).sortable({
                handle: '#move-row',
                placeholder: "sortable-hightligth",
                start: function (event, ui) {
                    ui.placeholder.height(ui.helper.outerHeight());
                    ui.placeholder.addClass(ui.item.attr('class'));
                },
                stop: function (event, ui) {
                    ui.placeholder.removeClass(ui.item.attr('class'));
                }
            }).disableSelection();
        },
        /**
         * Update selected option
         * @param {type} $element
         * @param {type} value
         * @returns {undefined}
         */
        _updateSelectBox: function ($element, value) {
            $element.find('option').each(function () {
                if ($(this).val() == value) {
                    $(this).prop('selected', true);
                } else {
                    $(this).removeAttr('selected');
                }
            });
            $element.trigger('change');
        },
        onJdocChange:function(element){
            var jdoc = $(element).val();
            jdoc = jdoc.toString().toLowerCase();
            var $positionField = this._getField(this._elements.settingPosition).closest(this._elements.controlGroup);
            if(jdoc.indexOf('module') < 0){
                $positionField.hide('slow');
            }else{
                $positionField.show('slow');
            }
        },
        /**
         * Get setting field
         * @returns {$jquery object}
         */
        _getField: function (selector) {
            return $(this._elements.rowSetting).find(selector);
        },
        /**
         * Sortable flush
         * @returns {undefined}
         */
        sortableFlush: function () {
            $(this._elements.layoutBuilderContainer).sortable("destroy");
            $(this._elements.childrenContainer).sortable("destroy");
        },
        /**
         * Add empty parent row
         * @returns {undefined}
         */
        addParentRow: function () {
            this.sortableFlush();
            var html = $(this._elements.emptyParentRow).html();
            $(this._elements.layoutBuilderContainer)
                    .find(this._elements.sortableRow + ':first')
                    .before(html);
            this._init();
        },
        /**
         * Add child row
         * @param {type} element
         * @returns {undefined}
         */
        addRow: function (element) {
            this.sortableFlush();
            var html = $(this._elements.emptyChildRow).html();
            var $childContainer = $(element)
                    .closest(this._elements.sortableRow)
                    .find(this._elements.childrenContainer + ':first');
            if ($childContainer.find(this._elements.sortableRow).length > 0) {
                $childContainer.find(this._elements.sortableRow + ':first').before(html);
            } else {
                $childContainer.html(html);
            }
            this._init();
        },
        showDeleteModal: function (element) {
            this.editingElement = $(element).closest(this._elements.sortableRow);
            $(this._elements.rowDelete).modal('show');
        },
        /**
         * Delete row
         * @param {html node} element
         * @returns {undefined}
         */
        deleteRow: function (element) {
            this.editingElement.remove();
            $(this._elements.joomlaTooltip).find(':visible').hide();
            $(this._elements.rowDelete).modal('hide');
        },
        /**
         * Resize row
         * @returns {undefined}
         */
        resize: function (element, action) {
            var _self = this;
            var $row = $(element).closest(this._elements.sortableRow);
            var data = $row.data('zo2');
            if (!data.hasOwnProperty('grid')) {
                data.grid = {xs: 12, sm: 12, md: 12, lg: 12};
            }
            $row.removeClass('span' + data.grid.md);
            if (action === 'increase') {
                data.grid = {
                    xs: _self._increase(data.grid.xs),
                    sm: _self._increase(data.grid.sm),
                    md: _self._increase(data.grid.md),
                    lg: _self._increase(data.grid.lg)
                };
            } else {
                data.grid = {
                    xs: _self._decrease(data.grid.xs),
                    sm: _self._decrease(data.grid.sm),
                    md: _self._decrease(data.grid.md),
                    lg: _self._decrease(data.grid.lg)
                };
            }
            $row.addClass('span' + data.grid.md);
            $row.data('zo2', data);
            $row.find(this._elements.rowSize + ':first').find(this._elements.rowWidth + ':first').html(data.grid.md + '/12');
        },
        /*
         * Increase row width
         * @param {integer} value
         * @returns {Number}
         */
        _increase: function (value) {
            return (value < 12) ? value += 1 : value;
        },
        /**
         * Decrease row width
         * @param {type} value
         * @returns {Number}
         */
        _decrease: function (value) {
            return (value > 1) ? value -= 1 : value;
        },
        /**
         * Show modal
         * @param {html node} element
         * @returns {undefined}
         */
        showSettingModal: function (element) {
            this.editingElement = $(element)
                    .closest(this._elements.sortableRow);
            var data = this.editingElement.data('zo2');
            /* Update name */
            if (data.hasOwnProperty('name')) {
                this._getField(this._elements.settingName).val(data.name);
            }
            /* Update jdoc */
            if (data.hasOwnProperty('jdoc')) {
                if (data.jdoc.hasOwnProperty('type')) {
                    this._updateSelectBox(this._getField(this._elements.settingJdoc), data.jdoc.type);
                }
                if (data.jdoc.hasOwnProperty('style')) {
                    this._updateSelectBox(this._getField(this._elements.settingStyle), data.jdoc.style);
                }
                if (data.jdoc.hasOwnProperty('name')) {
                    this._updateSelectBox(this._getField(this._elements.settingPosition), data.jdoc.name);
                }
            }
            /* Update offset */
            if (data.hasOwnProperty('offset')) {
                this._updateSelectBox(this._getField(this._elements.settingOffset), data.offset);
            }
            /* Update customize css clash */
            if (data.hasOwnProperty('class')) {
                this._getField(this._elements.settingCss).val(data.class);
            }
            $(this._elements.rowSetting).modal('show');
        },
        /**
         * Save row setting
         * @returns {undefined}
         */
        saveSetting: function () {
            var _self = this;
            var oldData = this.editingElement.data('zo2');
            var data = {
                name: _self._getField(_self._elements.settingName).val(),
                jdoc: {
                    name: _self._getField(_self._elements.settingPosition).val(),
                    type: _self._getField(_self._elements.settingJdoc).val(),
                    style: _self._getField(_self._elements.settingStyle).val(),
                },
                class: _self._getField(_self._elements.settingCss).val(),
                offset: _self._getField(_self._elements.settingOffset).val(),
            };
            if (typeof (oldData.visibility) !== 'undefined') {
                data.visibility = oldData.visibility;
            }
            if (typeof (oldData.gird) !== 'undefined') {
                data.gird = oldData.gird;
            }
            this.editingElement.data('zo2', data);
            $(this.editingElement).find(this._elements.parentContainer + ':first').find(this._elements.rowName + ':first').html('<span>' + _self._getField(_self._elements.settingName).val() + '</span>');
            $(this._elements.rowSetting).modal('hide');
        },
        /**
         * Internal get JSON
         * @param {jQuery object} $node
         * @param {array} parent
         * @returns {undefined}
         */
        _getLayoutJson: function ($node, parent) {
            var _self = this;
            if (typeof ($node) == 'undefined') {
                $node = $(_self._elements.layoutBuilderContainer);
                parent = _self.layoutJson;
            } else {
                $node = $node.find(_self._elements.childrenContainer + ':first');
            }
            $.each($node.children(_self._elements.sortableRow), function () {
                if (typeof ($(this).data('zo2')) != 'undefined') {
                    parent.push($(this).data('zo2'));
                    parent[parent.length - 1].children = [];
                    _self._getLayoutJson($(this), parent[parent.length - 1].children);
                }
            });
        },
        /**
         * Get layout JSON and convert
         * @param {string} type object/string
         * @returns {undefined}
         */
        getLayoutJson: function (type) {
            this.layoutJson = [];
            type = (typeof (type) === 'undefined') ? 'object' : type;
            this._getLayoutJson();
            return (type === 'string') ? w.JSON.stringify(this.layoutJson) : this.layoutJson;
        },
        /**
         * Update JSON field
         * @returns {undefined}
         */
        updateJson: function () {
            $(this._elements.jsonField).val(this.getLayoutJson('string'));
        }
    };

    /* Append to Zo2 JS framework */
    z.layoutbuilder = _layoutbuilder;

    /* Init after document ready */
    $(w.document).ready(function () {
        z.layoutbuilder._init();
    });

})(window, zo2, jQuery);