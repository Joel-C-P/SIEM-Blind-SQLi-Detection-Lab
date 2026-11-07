## Indicators and Observed Artifacts

The following indicators and artifacts supported the investigation.They are specific to this controlled lab and should be interpreted
together with the request payload and application behavior.

| Type | Observed value | Investigative relevance |
|---|---|---|
| Observed source IP | `127.0.0.12` | Source associated with the SQLi requests in the reviewed dataset. Loopback address used in the lab. |
| Target address | `127.0.0.1:8080` | Destination web service. Identifies the affected asset, not attacker infrastructure. |
| Target host | `web_lab` | Host associated with the ModSecurity events in Splunk. |
| Target endpoint | `GET /searchUsers.php` | Endpoint receiving the injection requests. |
| Injection parameter | `id` | User-controlled input carrying the SQL expressions. |
| User-Agent | `python-requests/2.32.3` | Consistent with the Python automation used in the test. Not malicious by itself. |
| Payload pattern | Nested `SELECT` statements with `GROUP_CONCAT`, `ASCII`, and `SUBSTRING` | Pattern used to infer database values character by character. |
| Targeted data | `username` and `password` columns in the `users` table | Identifies the data targeted by the extraction queries. |
| SQLi rule IDs | `942100`, `942360` | ModSecurity / OWASP CRS detections supporting the initial triage. |
| Example transaction ID | `auhvr_8Ubm0aUjNNem_3fQAAAAE` | Reference to an individual request for evidence retrieval and correlation. |

### Behavioral Indicators

- Repeated requests to the same endpoint with changing character
  positions and numeric comparison values.
- SQL expressions attempting to infer concatenated usernames and passwords.
- A concentrated sequence of 3,034 matching requests within an observed
  7.144-second window.
- HTTP response differences that provided a boolean inference signal
  under the validated laboratory conditions.

