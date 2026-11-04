# Playbook: Blind SQL Injection Investigation (SOC Tier 1)

## 1. Objective
Investigate a ModSecurity alert for a suspected blind SQL injection against a web application, determine whether it is a false positive, an authorized test, or a real attack, and escalate with sufficient evidence if needed.

## 2. Detection Condition
ModSecurity (in DetectionOnly mode) triggers one or more SQLi rules (e.g., 942100, 942360) on a request to a web endpoint. The alert is forwarded to Splunk. Typical indicators:
- URI contains SQL keywords/functions (SELECT, UNION, GROUP_CONCAT, SUBSTRING, ASCII, others.)
- Parameter value includes quotes, logical operators, or nested queries.
- User-Agent may be a scripting tool (sqlmap, personalized script, others).
- HTTP response code may be 200, 404, or 500.

## 3. Initial Triage
1. Open the alert in Splunk. Note the transaction ID, timestamp, source IP, destination IP/port, and triggered rule IDs.
2. Ask: What triggered the detection? Does the payload look like a legitimate request or an attack? Which system is affected?
3. Filter the ModSecurity audit log (`/var/log/apache2/modsec_audit.log`) for the transaction ID to extract full request details.
4. If the payload clearly contains SQL injection syntax, treat as suspicious. Do not attempt to confirm exploitation at Tier 1.
5. Create a Jira ticket immediately if the attack appears critical (e.g., targeting authentication or user data). Include:
   - Alert name and time
   - Source IP and targeted endpoint
   - Payload summary and triggered rules
   - Link to Splunk search
6. Escalate to Tier 2 in parallel while continuing investigation per this playbook.

## 4. Validate Evidence
In Splunk:
1. Review the raw ModSecurity event(s) for the transaction ID.
2. Confirm the HTTP method, URI, parameter name, and full payload.
3. Check the HTTP response code and Content-Length. A 404 or empty response does not prove failure or success.
4. Look for multiple requests from the same source IP within a short time window (e.g., seconds). Use `stats count by src_ip, uri, status` or similar.
5. Correlate with Apache access logs (`/var/log/apache2/access.log`) for the same timestamp and source IP.
6. Do not assume data extraction occurred. Blind SQLi may return identical responses regardless of injection success.

## 5. Context Checking
- Identify the affected host/service (e.g., `web_lab`, `searchUsers.php`).
- Determine if the source IP is internal, external, or a known scanner.
- Review other alerts from the same source IP or targeting the same endpoint.
- Check if the User-Agent matches known attack tools (`python-requests`, `sqlmap`, others).
- If Suricata is deployed and did not alert, note this as a coverage gap but do not dismiss the ModSecurity alert.

## 6. Decision and Priority
| Situation | Decision |
|-----------|----------|
| False positive (e.g., benign input triggering rule) | Document and suggest rule tuning. |
| Suspicious payload but no evidence of success | Escalate to Tier 2 with medium priority. |
| Suspicious payload with signs of successful exploitation (e.g., unusual response times, data in responses, subsequent anomalies) | Escalate to Tier 2 with high priority. |
| Multiple distinct attack requests or targeting sensitive data | Escalate immediately with high priority. |

Priority may increase if the target handles authentication or financial data.

## 7. Escalation to Tier 2
Include in the escalation:
- Summary of the attack (what, when, where).
- Source IP, destination, URI, parameter, payload(s).
- Triggered ModSecurity rule IDs and anomaly scores.
- HTTP response codes observed.
- Jira ticket reference.
- Steps already taken and their results.
- Priority and justification.

Do not claim successful exploitation or data breach without evidence.

## 8. Documentation
In the Jira ticket, record:
- Final classification (true positive, false positive, authorized test).
- Actions taken (escalation, containment if authorized).
- Status (Pending, Investigate, Cancel, Resolve).
