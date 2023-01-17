<?php
const emgDeadRoutes = array("639c2b01f02f467545ff3428");
const allCompenentIds = array("639c2b01f02f467545ff3428", "639c2aaff02f467549a5a92f", "639c29eaf02f467549a5a921");


function reportIssues() {
    foreach (allCompenentIds as $componentId) {
        $issues = gettingIssues($componentId);
        if (count($issues) <= 0) {
            if (in_array($componentId, $emgDeadRoutes)) {
                creatingIssue($componentId);
            }
        } else {
            if (!in_array($componentId, $emgDeadRoutes)) {
                foreach ($issues as $issue) {
                    deletingIssue($issue['id']);
                }
            }
        }
    }
}




function creatingIssue($idOfComponent){


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://beem.hund.io/api/v1/issues',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "title": "Dead Route",
  "body": "Reported Dead Route",
  "label": "maintenance",
  "components": [
    $idOfComponent
  ]
}',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic d0ZNQlR0eTQwcHBVNE5KNko4ZkNQV2RweW5UQUw3VW1RS0lSNHcxdDVpWWtrOg==',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

}




function deletingIssue($issueID){
    

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://beem.hund.io/api/v1/issues/${issueID}', // confirm this with Salehe
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'DELETE',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic d0ZNQlR0eTQwcHBVNE5KNko4ZkNQV2RweW5UQUw3VW1RS0lSNHcxdDVpWWtrOg=='
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

};

reportingIssues();


async function gettingIssues($componentId) {
    try {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://beem.hund.io/api/v1/issues?limit=100&components=' . $componentId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic d0ZNQlR0eTQwcHBVNE5KNko4ZkNQV2RweW5UQUw3VW1RS0lSNHcxdDVpWWtrOg=='
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new Exception(curl_error($curl));
        }

        curl_close($curl);
        return json_decode($response.data.data);
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
