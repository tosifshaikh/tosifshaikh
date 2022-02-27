(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[1],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/tags.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/pages/tags.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  name: "tag",
  data: function data() {
    return {
      tagData: {
        tagName: ''
      },
      tags: [],
      AddModal: false,
      EditModal: false,
      isAdding: false,
      editTagData: {
        tagName: ''
      },
      index: -1,
      showDeleteModal: false,
      deleteItem: {},
      deleteIndex: -1,
      modalLoading: false
    };
  },
  methods: {
    addTag: function addTag() {
      var _this = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee() {
        var res;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                if (!(_this.tagData.tagName.trim() == '')) {
                  _context.next = 2;
                  break;
                }

                return _context.abrupt("return", _this.error('Tag Name is required'));

              case 2:
                _context.next = 4;
                return _this.callApi('post', '/app/create_tag', _this.tagData);

              case 4:
                res = _context.sent;

                if (res.status == 201) {
                  _this.tags.unshift(res.data);

                  _this.success('Tag has been added successfully!');

                  _this.AddModal = false;
                } else {
                  if (res.status == 422) {
                    if (res.data.errors.tagName) {
                      _this.info(res.data.errors.tagName[0]);
                    }
                  } else {
                    _this.error('Some error occured');
                  }
                }

                _this.tagData = {};

              case 7:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    },
    editTag: function editTag() {
      var _this2 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee2() {
        var res;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                if (!(_this2.editTagData.tagName.trim() == '')) {
                  _context2.next = 2;
                  break;
                }

                return _context2.abrupt("return", _this2.error('Tag Name is required'));

              case 2:
                _context2.next = 4;
                return _this2.callApi('post', '/app/edit_tag', _this2.editTagData);

              case 4:
                res = _context2.sent;

                if (res.status == 200) {
                  _this2.tags[_this2.index].tagName = _this2.editTagData.tagName;

                  _this2.success('Tag has been edited successfully!');

                  _this2.EditModal = false;
                } else {
                  if (res.status == 422) {
                    if (res.data.errors.tagName) {
                      _this2.info(res.data.errors.tagName[0]);
                    }
                  } else {
                    _this2.error('Some error occured');
                  }
                }

              case 6:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2);
      }))();
    },
    showEditModal: function showEditModal(tag, index) {
      var obj = {
        id: tag.id,
        tagName: tag.tagName
      };
      this.editTagData = obj;
      this.EditModal = true;
      this.index = index;
    },
    showDeletingModal: function showDeletingModal(tag, index) {
      this.deleteItem = tag;
      this.deleteIndex = index;
      this.showDeleteModal = true;
    },
    deleteTag: function deleteTag() {
      var _this3 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee3() {
        var res;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee3$(_context3) {
          while (1) {
            switch (_context3.prev = _context3.next) {
              case 0:
                _this3.modalLoading = true;
                _context3.next = 3;
                return _this3.callApi('post', 'app/delete_tag', _this3.deleteItem);

              case 3:
                res = _context3.sent;

                if (res.status == 200) {
                  _this3.tags.splice(_this3.deleteIndex, 1);

                  _this3.success('Tag has been deleted successfully!');
                } else {
                  _this3.error('Some error occured');
                }

                _this3.modalLoading = false;
                _this3.showDeleteModal = false;

              case 7:
              case "end":
                return _context3.stop();
            }
          }
        }, _callee3);
      }))();
    }
  },
  created: function created() {
    var _this4 = this;

    return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee4() {
      var res;
      return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee4$(_context4) {
        while (1) {
          switch (_context4.prev = _context4.next) {
            case 0:
              _context4.next = 2;
              return _this4.callApi('get', '/app/get_tag');

            case 2:
              res = _context4.sent;

              if (res.status == 200) {
                _this4.tags = res.data;
              }

            case 4:
            case "end":
              return _context4.stop();
          }
        }
      }, _callee4);
    }))();
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/tags.vue?vue&type=template&id=d06163e2&":
/*!*************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/pages/tags.vue?vue&type=template&id=d06163e2& ***!
  \*************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _vm._m(0),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-xl-12" }, [
        _c(
          "div",
          { staticClass: "card" },
          [
            _c(
              "div",
              { staticClass: "card-header" },
              [
                _c(
                  "Button",
                  {
                    on: {
                      click: function ($event) {
                        _vm.AddModal = true
                      },
                    },
                  },
                  [
                    _c("Icon", { attrs: { type: "md-add" } }),
                    _vm._v(" Add tag"),
                  ],
                  1
                ),
              ],
              1
            ),
            _vm._v(" "),
            _c("div", { staticClass: "card-body table-border-style" }, [
              _c("div", { staticClass: "table-responsive" }, [
                _c("table", { staticClass: "table" }, [
                  _vm._m(1),
                  _vm._v(" "),
                  _c(
                    "tbody",
                    [
                      _vm._l(_vm.tags, function (tag, i) {
                        return _c("tr", { key: i }, [
                          _c("td", [_vm._v(_vm._s(i + 1))]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(tag.tagName))]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(tag.created_at))]),
                          _vm._v(" "),
                          _c(
                            "td",
                            [
                              _c(
                                "Button",
                                {
                                  attrs: { type: "info", size: "small" },
                                  on: {
                                    click: function ($event) {
                                      return _vm.showEditModal(tag, i)
                                    },
                                  },
                                },
                                [_vm._v("Edit")]
                              ),
                              _vm._v(" "),
                              _c(
                                "Button",
                                {
                                  attrs: {
                                    type: "error",
                                    size: "small",
                                    loading: tag.isDeleteting,
                                  },
                                  on: {
                                    click: function ($event) {
                                      return _vm.showDeletingModal(tag, i)
                                    },
                                  },
                                },
                                [_vm._v("Delete")]
                              ),
                            ],
                            1
                          ),
                        ])
                      }),
                      _vm._v(" "),
                      _vm.tags.length <= 0
                        ? _c("tr", [_c("td", [_vm._v("No Data")])])
                        : _vm._e(),
                    ],
                    2
                  ),
                ]),
              ]),
            ]),
            _vm._v(" "),
            _c(
              "Modal",
              {
                attrs: {
                  title: "Add Tag",
                  "mask-closable": false,
                  closable: false,
                },
                model: {
                  value: _vm.AddModal,
                  callback: function ($$v) {
                    _vm.AddModal = $$v
                  },
                  expression: "AddModal",
                },
              },
              [
                _c("Input", {
                  staticStyle: { width: "300px" },
                  attrs: { placeholder: "Add Tag Name" },
                  model: {
                    value: _vm.tagData.tagName,
                    callback: function ($$v) {
                      _vm.$set(_vm.tagData, "tagName", $$v)
                    },
                    expression: "tagData.tagName",
                  },
                }),
                _vm._v(" "),
                _c(
                  "div",
                  { attrs: { slot: "footer" }, slot: "footer" },
                  [
                    _c(
                      "Button",
                      {
                        attrs: { type: "default" },
                        on: {
                          click: function ($event) {
                            _vm.AddModal = false
                          },
                        },
                      },
                      [_vm._v("Close")]
                    ),
                    _vm._v(" "),
                    _c(
                      "Button",
                      {
                        attrs: {
                          type: "primary",
                          disabled: _vm.isAdding,
                          loading: _vm.isAdding,
                        },
                        on: { click: _vm.addTag },
                      },
                      [_vm._v(_vm._s(_vm.isAdding ? "Adding..." : "Add Tag"))]
                    ),
                  ],
                  1
                ),
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "Modal",
              {
                attrs: {
                  title: "Edit Tag",
                  "mask-closable": false,
                  closable: false,
                },
                model: {
                  value: _vm.EditModal,
                  callback: function ($$v) {
                    _vm.EditModal = $$v
                  },
                  expression: "EditModal",
                },
              },
              [
                _c("Input", {
                  staticStyle: { width: "300px" },
                  attrs: { placeholder: "Edit Tag Name" },
                  model: {
                    value: _vm.editTagData.tagName,
                    callback: function ($$v) {
                      _vm.$set(_vm.editTagData, "tagName", $$v)
                    },
                    expression: "editTagData.tagName",
                  },
                }),
                _vm._v(" "),
                _c(
                  "div",
                  { attrs: { slot: "footer" }, slot: "footer" },
                  [
                    _c(
                      "Button",
                      {
                        attrs: { type: "default" },
                        on: {
                          click: function ($event) {
                            _vm.EditModal = false
                          },
                        },
                      },
                      [_vm._v("Close")]
                    ),
                    _vm._v(" "),
                    _c(
                      "Button",
                      {
                        attrs: {
                          type: "primary",
                          disabled: _vm.isAdding,
                          loading: _vm.isAdding,
                        },
                        on: { click: _vm.editTag },
                      },
                      [_vm._v(_vm._s(_vm.isAdding ? "Editing..." : "Edit Tag"))]
                    ),
                  ],
                  1
                ),
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "Modal",
              {
                attrs: { width: "360" },
                model: {
                  value: _vm.showDeleteModal,
                  callback: function ($$v) {
                    _vm.showDeleteModal = $$v
                  },
                  expression: "showDeleteModal",
                },
              },
              [
                _c(
                  "p",
                  {
                    staticStyle: { color: "#f60", "text-align": "center" },
                    attrs: { slot: "header" },
                    slot: "header",
                  },
                  [
                    _c("Icon", { attrs: { type: "ios-information-circle" } }),
                    _vm._v(" "),
                    _c("span", [_vm._v("Delete confirmation")]),
                  ],
                  1
                ),
                _vm._v(" "),
                _c("div", { staticStyle: { "text-align": "center" } }, [
                  _c("p", [
                    _vm._v("Are you sure you want to delete this tag?."),
                  ]),
                ]),
                _vm._v(" "),
                _c(
                  "div",
                  { attrs: { slot: "footer" }, slot: "footer" },
                  [
                    _c(
                      "Button",
                      {
                        attrs: {
                          type: "error",
                          size: "large",
                          long: "",
                          loading: _vm.modalLoading,
                        },
                        on: { click: _vm.deleteTag },
                      },
                      [_vm._v("Delete")]
                    ),
                  ],
                  1
                ),
              ]
            ),
          ],
          1
        ),
      ]),
    ]),
  ])
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "page-header" }, [
      _c("div", { staticClass: "page-block" }, [
        _c("div", { staticClass: "row align-items-center" }, [
          _c("div", { staticClass: "col-md-12" }, [
            _c("div", { staticClass: "page-header-title" }, [
              _c("h5", { staticClass: "m-b-10" }, [_vm._v("Tags")]),
            ]),
            _vm._v(" "),
            _c("ul", { staticClass: "breadcrumb" }, [
              _c("li", { staticClass: "breadcrumb-item" }, [
                _c("a", { attrs: { href: "index.html" } }, [
                  _c("i", { staticClass: "feather icon-home" }),
                ]),
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "breadcrumb-item" }, [
                _c("a", { attrs: { href: "#!" } }, [_vm._v("Dashboard")]),
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "breadcrumb-item" }, [
                _c("a", { attrs: { href: "#!" } }, [_vm._v("Tags")]),
              ]),
            ]),
          ]),
        ]),
      ]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("th", [_vm._v("#")]),
        _vm._v(" "),
        _c("th", [_vm._v("Tag Name")]),
        _vm._v(" "),
        _c("th", [_vm._v("Created At")]),
        _vm._v(" "),
        _c("th", [_vm._v("Actions")]),
      ]),
    ])
  },
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/pages/tags.vue":
/*!************************************************!*\
  !*** ./resources/js/components/pages/tags.vue ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _tags_vue_vue_type_template_id_d06163e2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./tags.vue?vue&type=template&id=d06163e2& */ "./resources/js/components/pages/tags.vue?vue&type=template&id=d06163e2&");
/* harmony import */ var _tags_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./tags.vue?vue&type=script&lang=js& */ "./resources/js/components/pages/tags.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _tags_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _tags_vue_vue_type_template_id_d06163e2___WEBPACK_IMPORTED_MODULE_0__["render"],
  _tags_vue_vue_type_template_id_d06163e2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/pages/tags.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/pages/tags.vue?vue&type=script&lang=js&":
/*!*************************************************************************!*\
  !*** ./resources/js/components/pages/tags.vue?vue&type=script&lang=js& ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_tags_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./tags.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/tags.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_tags_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/pages/tags.vue?vue&type=template&id=d06163e2&":
/*!*******************************************************************************!*\
  !*** ./resources/js/components/pages/tags.vue?vue&type=template&id=d06163e2& ***!
  \*******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_tags_vue_vue_type_template_id_d06163e2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./tags.vue?vue&type=template&id=d06163e2& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/pages/tags.vue?vue&type=template&id=d06163e2&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_tags_vue_vue_type_template_id_d06163e2___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_tags_vue_vue_type_template_id_d06163e2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);