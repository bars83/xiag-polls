require('jquery.cookie');

import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';

import io from 'socket.io-client';

let socket = io(window.location.protocol + '//' + window.location.host);

$('#poll_form').submit(function (e) {
    if ($.cookie('xiag_poll_' + uid) === "1") {
        $(this).remove();
        alert('You already vote!');
    } else
        axios.post('/vote', $(this).serialize()).then(() => {
                $.cookie('xiag_poll_' + uid, 1);
                $(this).remove();
            }
        ).catch((exception) => {
            console.log(exception);
            alert('error sending your vote');
        });
    e.preventDefault();
});

class Results extends Component {
    constructor(props) {
        super(props);
        this.state = {
            answers: this.props.answers,
            results: this.props.results
        }
    }

    componentWillMount() {
        const uid = this.props.uid;
        socket.on('connect', function () {
            socket.emit('room', uid);
        });

        socket.on('message', this.handleData)
    }

    handleData = (data) => {
        this.setState(JSON.parse(data));
    };


    renderTableData = () => {
        return (
            <table className="ex2-table">
                <thead>
                <tr>
                    <th>Name</th>
                    {this.state.answers.map((answer, index) => {
                        return <th key={index}>{answer.value}</th>
                    })}
                </tr>
                </thead>
                <tbody>
                {this.state.results.map((result, index) => {
                    return <tr key={index}>
                        <td>{result.user_name}</td>
                        {
                            this.state.answers.map((answer, index) => {
                                return <td key={index}>{answer.id === result.answer_id ? 'x' : ''}</td>
                            })
                        }
                    </tr>;

                })}
                </tbody>
            </table>
        );

    };


    render() {
        return (this.state.results.length > 0 ? this.renderTableData() : <div>There is no results yet</div>)
    }
}

const loadResults = () => {
    ReactDOM.render(
        <Results uid={uid} answers={answers} results={results}/>,
        document.getElementById('results-table')
    );
};

export default loadResults;
