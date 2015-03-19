var converter = new Showdown.converter();
var MarkdownEditor = React.createClass({
    getInitialState: function() {
        return {value: this.props.content};
    },
    handleChange: function() {
        this.setState({value: this.refs.editor.getDOMNode().value});
    },
    handleEditorScroll: function(scroll) {
        this.setNodeScrollFromNode(this.refs.preview.getDOMNode(), this.refs.editor.getDOMNode());
    },
    setNodeScrollFromNode: function(node, node_from){
        fraction = (node_from.scrollTop / node_from.scrollHeight) * 100;
        node.scrollTop = (node.scrollHeight * fraction) / 100;
    },
    render: function() {
        var raw = converter.makeHtml(this.state.value);

        return (
            <div className="markdown-editor">
                <textarea
                    ref="editor"
                    className="raw col-lg-6 col-md-6 col-sm-6"
                    defaultValue={this.state.value}
                    onChange={this.handleChange}
                    onScroll={this.handleEditorScroll}></textarea>
                <div ref="preview" className="preview well col-lg-6 col-md-6 col-sm-6" dangerouslySetInnerHTML={{__html: raw}}></div>
            </div>
        )
    },
});