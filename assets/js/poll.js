import '../css/style.css';

import React, {Component} from 'react';
import ReactDOM from 'react-dom';


class Table extends Component {
    constructor(props) {
        super(props);
        this.state = {
            answers: [
                {id: 1, value: ''},
                {id: 2, value: ''}
            ]
        }
    }

    removeRow = (answer) => {
        let nextState = this.state;
        if (nextState.answers.length <= 2)
            return;
        let index = this.state.answers.indexOf(answer);
        nextState.answers.splice(index, 1);
        this.setState(nextState);
    };

    addRow = () => {
        let nextState = this.state;
        let maxId = nextState.answers[nextState.answers.length - 1].id + 1;
        nextState.answers.push({id: maxId, value: ''});
        this.setState(nextState);
    };


    renderTableData = () => {
        return this.state.answers.map((answer, index) => {
            const {id, value} = answer;
            return (
                <tr key={id}>
                    <th>Answer {index + 1}</th>
                    <td>
                        <input type="text" name={'answer[' + id + ']'} defaultValue={value} className="input-text" required={"required"}/>
                    </td>
                    <td>
                        {this.state.answers.length > 2 &&
                        <button className="btn btn--plus" type="button"
                                onClick={() => this.removeRow(answer)}>
                            -
                        </button>
                        }
                    </td>
                </tr>
            )
        })

    };


    render() {
        return ([
                this.renderTableData(),
                <tr key={-1}>
                    <td colSpan="3" className="poll-table__plus">
                        <button className="btn btn--plus" type="button" onClick={() => this.addRow()}>
                            +
                        </button>
                    </td>
                </tr>
            ]
        )
    }
}

const loadTable = () => {
    ReactDOM.render(
        <Table/>,
        document.getElementById('table')
    );
};


export default loadTable;
