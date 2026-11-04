# Blind SQL Injection — SOC Analyst Investigation Lab

Investigating suspected blind SQL injection against a PHP application backed by MariaDB, using **Splunk** for log analysis, **ModSecurity** for initial detection, and **Jira** for case documentation and simulated Tier 2 escalation.

This project focuses on the **SOC Tier 1 investigation workflow**: validating an alert, reconstructing suspicious activity, assessing potential impact, and escalating with actionable evidence.

## Lab Components

| Component | Role |
|---|---|
| Splunk (SIEM) | Centralized log analysis and investigation |
| ModSecurity (WAF)| Initial web attack detection in DetectionOnly mode |
| Suricata (IDS/IPS)| Network monitoring and detection coverage assessment |
| PHP application (Backend connected with ddbb)| Target web application |
| MariaDB SQL (Data Base) | Application database |
| Python script (Automated attack from pentester)| Controlled attack simulation |
| Jira (Ticket software)| Case documentation and simulated escalation |

## Scenario

A Python script sends SQL injection requests to a deliberately vulnerable PHP application in a controlled lab environment.

ModSecurity operates in **DetectionOnly** mode: it identifies suspicious requests without blocking them. Its events are ingested into Splunk, where the investigation begins.

Suricata is also deployed but does not initially generate alerts for the tested attack. The investigation considers sensor visibility and detection coverage before interpreting this absence of alerts.

## Investigation Objectives

- Validate the initial ModSecurity detection against the underlying request data.
- Identify the targeted endpoint, parameter, and observed source.
- Reconstruct the request timeline and examine response patterns.
- Assess whether the evidence supports an SQL injection attempt, successful exploitation, or data extraction.
- Document findings, uncertainty, and investigation limits in Jira.
- Perform a simulated Tier 2 escalation when the evidence or risk warrants further investigation.

## Scope

A later improvement phase will introduce additional Suricata rules and compare detection results against the initial configuration.

## 1. Initial Alert and Triage

It's a normal day and while reviewing events, I see one from ModSecurity, and I ask myself a couple of questions before continuing: What triggered the detection? Does the detection make sense or is it a false positive? What system does it affect? And what impact does it present for the company? Taking a superficial look, I start seeing all the detection parameters.

![ModSecurity Detection](screenshots/00_deteccion.png)

I begin the part of filtering the most relevant information, for example I take the detection identifier.

![Simple filter](screenshots/01_simple_filter.png)

