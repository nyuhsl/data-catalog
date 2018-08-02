import urllib2
import json
import csv
from datetime import date

###
# 
#  Fill in the URL to your Solr core and to your data catalog installation here
# 
### 
solr_core_url = 'https://www.example.com/solr/data_catalog'
data_catalog_base_url = 'https://www.example.com'


db_output_url = data_catalog_base_url + '/api/Dataset/all.json?output_format=solr'
solr_output_url = solr_core_url + '/select/?q=*:*&wt=json'
solr_submit_url = solr_core_url + '/update/json?commit=true&overwrite=true'
solr_remove_url = solr_core_url + '/update/?commit=true'

db_response = urllib2.urlopen(db_output_url)
db_json_output = db_response.read()
db_parsed_json=json.loads(db_json_output)

solr_response = urllib2.urlopen(solr_output_url)
solr_json_output = solr_response.read()

#
# Find items to remove

for row in json.loads(solr_json_output)['response']['docs']:
  if not [x for x in db_parsed_json if x['id'] == int(row['id'])]:
    
    to_solr="{'delete': {'id': "+row['id']+"}}"
    print "delete "+row['id']
    request = urllib2.Request(solr_remove_url, to_solr.encode("utf-8"), {'Content-Type': 'application/json'})
    try:
      urllib2.urlopen(request)
    except urllib2.HTTPError as e:
      print e.code
      print e.read()

#
# Find new/added items

for row in db_parsed_json:

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
  request = urllib2.Request(solr_submit_url, to_solr.encode("utf-8"), {'Content-Type': 'application/json'})


  try:
    urllib2.urlopen(request)
  except urllib2.HTTPError as e:
    print e.code
    print e.read()
