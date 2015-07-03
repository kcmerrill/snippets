angular.module('snippetsApp', [])
.factory('snippet', function($http, editor){
    return {
        editor: editor,
        _id: false,
        tags: '',
        code: '',
        filename: '',
        link: false,
        fetch: function(id, callback) {
            var self = this;
            $http.get('/' + id + '/raw').
            success(function(data, status, headers, config) {
                self._id = data._id;
                self.code = data.snippet;
                editor.set(data.snippet);
                callback(data.type);
            });
        },
        /* Ace caption, and Ace content */
        save: function(type, content, tags){
            var self = this;
            $http.post('/save', {
                _id: self._id,
                type: type,
                snippet: content,
            }).
            success(function(data, status, headers, config) {
                if(data._id != self._id) {
                    window.location = '/' + data._id;
                }
            }).
            error(function(data, status, headers, config) {
            });
        },
        link: function() {},
        fork: function(type, content, tags){
            var self = this;
            self._id = false;
            self.save(type, content, tags);
        }
    };
})
.factory('editor', function(){
    return {
        filename: '',
        set: function(code) {
            var editor = ace.edit("editor");
            editor.setValue(code, 1);
        },
        /* REFACTOR: I must have confused keyboard handler with type. */
        mode: function(mode) {
            /* Update our editor mode */
            var editor = ace.edit("editor");
            editor.setKeyboardHandler(mode);
        },
        types: function() {
            /* Update our editor type(think: document type) */
            var editor = ace.edit("editor");
            return ace.require("ace/ext/modelist").modes;
        },
        type: function(mode) {
            /* Update our editor type */
            var editor = ace.edit("editor");
            var modelist = ace.require("ace/ext/modelist");
            editor.session.setMode(mode);
        }
    };
})
.controller('editorCtrl', function($scope, snippet) {
    $scope.snippet = snippet;
})
.controller('savingCtrl', function($scope) {
})
.controller('menuCtrl', function($scope, snippet, editor) {
    $scope.snippet = snippet;
    $scope.editor = editor;
    $scope.types = editor.types();
    $scope.type = _.find($scope.types, {name: 'text'});
    editor.type($scope.type.mode);
    $scope.fetch = function(_id) {
        if(_id) {
             $scope.snippet.fetch(_id, function(type) {
                $scope.type = _.find($scope.types, { name: type });
                editor.type($scope.type.mode);
            });
        }
    };
    $scope.download = function() {
        window.location = '/' + $scope.snippet._id + '/download';
    };
    $scope.fork = function() {
        snippet.fork($scope.type.name, ace.edit("editor").getSession().getValue(), $scope.tags);
    };
    $scope.save = function() {
        snippet.save($scope.type.name, ace.edit("editor").getSession().getValue(), $scope.tags);
    };
});
