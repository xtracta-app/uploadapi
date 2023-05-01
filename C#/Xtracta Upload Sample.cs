using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using RestSharp;

namespace XtractaUploadSample
{
    class Program
    {
        static void Main(string[] args)
        {
            string apiKey = "YOUR_API_KEY";
            int workflowId = YOUR_WORKFLOW_ID;
            string filePath = "C:/path/to/file.pdf";
            string url = "https://api-app.xtracta.com/v1/documents/upload";

            var Client = new RestClient();
            var req = new RestRequest(url, Method.POST);
            req.AddParameter("api_key", apiKey);
            req.AddParameter("workflow_id", workflowId);
            req.AddFile("userfile", filePath);
            IRestResponse response = Client.Execute(req);

            Console.WriteLine(response.StatusCode.ToString());
        }
    }
}