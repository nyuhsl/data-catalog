import urllib2
import json
import csv
from datetime import date


output_url = '<BASE URL OF YOUR DATA CATALOG INSTALLATION>/api/dataset/all.json'
submit_url = '<BASE URL OF YOUR SOLR INSTALLATION>/solr/data_catalog/update/json?commit=true&overwrite=true'


response = urllib2.urlopen(output_url)
json_output = response.read()

for row in json.loads(json_output):
  if (row['dataset_end_date'] and row['dataset_start_date']):
    end = row['dataset_end_date']
    if (end == 'Present'):
      end = date.today().year + 1
    end = int(end)
    start = int(row['dataset_start_date'])
    if start == end:
      row['dataset_years'] = [str(start)+'-01-01T00:00:00Z',str(end)+'-01-01T00:00:00Z']
    else:
      years = range(start,end)
      years = [str(v)+'-01-01T00:00:00Z' for v in years]
      row['dataset_years'] = years
  for item in row:
    if not row[item]:
      row[item] = ''
  if (row['date_added']):
    from_symfony = row['date_added']['date']
    solr_date = from_symfony.split()[0].strip() + 'T00:00:00Z'
    row['date_added'] = solr_date
  to_solr = json.dumps(row)
  to_solr = "[" + to_solr + "]"
  print to_solr
  #print row["id"]
  request = urllib2.Request(submit_url, to_solr.encode("utf-8"), {'Content-Type': 'application/json'})


  try:
    urllib2.urlopen(request)
  except urllib2.HTTPError as e:
    print e.code
    print e.read()

