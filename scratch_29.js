const axios = require('axios');
const emgDeadRoutes = ["639c2b01f02f467545ff3428"];
const allCompenentIds = ["639c2b01f02f467545ff3428", "639c2aaff02f467549a5a92f", "639c29eaf02f467549a5a921"];

const reportIssues = () => {
    allCompenentIds.forEach(componentId => {
        gettingIssues(componentId)
            .then((issues => {
                if (issues.length <= 0) {
                    if (emgDeadRoutes.some(deadRoute => deadRoute === componentId)) {
                        creatingIssue(componentId);
                    }
                } else {
                    if (emgDeadRoutes.some(deadRoute => deadRoute !== componentId)) {
                        issues.forEach(issue => deleteIssue(issue.id));
                    }
                }
            }));
    });

}

function gettingIssues(compenentId) {
    const config = {
        method: 'get',
        url: `https://beem.hund.io/api/v1/issues?limit=100&compenents=${compenentId}`,
        headers: {
            'Authorization': 'Basic d0ZNQlR0eTQwcHBVNE5KNko4ZkNQV2RweW5UQUw3VW1RS0lSNHcxdDVpWWtrOg=='
        }
    };

    return axios(config)
        .then(response => response.data.data)
        .catch(error => console.log({ errorFetchingIssues: error }));
}

function creatingIssue(idOfComponent) {

    const data = JSON.stringify({
        "title": "Dead Route",
        "body": "Reported Dead Route",
        "label": "maintenance",
        "components": [
            idOfComponent
        ]
    });

    const config = {
        method: 'post',
        url: 'https://beem.hund.io/api/v1/issues',
        headers: {
            'Authorization': 'Basic d0ZNQlR0eTQwcHBVNE5KNko4ZkNQV2RweW5UQUw3VW1RS0lSNHcxdDVpWWtrOg==',
            'Content-Type': 'application/json'
        },
        data: data
    };

    axios(config)
        .then(function (response) {
            console.log(JSON.stringify(response.data));
        })
        .catch(function (error) {
            console.log(error);
        });

}

function deleteIssue(issueID) {

    const config = {
        method: 'delete',
        url: `https://beem.hund.io/api/v1/issues/${issueID}`,
        headers: {
            'Authorization': 'Basic d0ZNQlR0eTQwcHBVNE5KNko4ZkNQV2RweW5UQUw3VW1RS0lSNHcxdDVpWWtrOg=='
        }
    };

    axios(config)
        .then(function (response) {
            console.log(JSON.stringify(response.data));
        })
        .catch(function (error) {
            console.log(error);
        });

}

reportIssues();
