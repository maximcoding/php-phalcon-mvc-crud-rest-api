<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class AccessGroupController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for access_group
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "AccessGroup", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $access_group = AccessGroup::find($parameters);
        if (count($access_group) == 0) {
            $this->flash->notice("The search did not find any access_group");

            return $this->dispatcher->forward(array(
                "controller" => "access_group",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $access_group,
            "limit"=> 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a access_group
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $access_group = AccessGroup::findFirstByid($id);
            if (!$access_group) {
                $this->flash->error("access_group was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "access_group",
                    "action" => "index"
                ));
            }

            $this->view->id = $access_group->id;

            $this->tag->setDefault("id", $access_group->id);
            $this->tag->setDefault("name", $access_group->name);
            $this->tag->setDefault("rules", $access_group->rules);
            
        }
    }

    /**
     * Creates a new access_group
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "access_group",
                "action" => "index"
            ));
        }

        $access_group = new AccessGroup();

        $access_group->id = $this->request->getPost("id");
        $access_group->name = $this->request->getPost("name");
        $access_group->rules = $this->request->getPost("rules");
        

        if (!$access_group->save()) {
            foreach ($access_group->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "access_group",
                "action" => "new"
            ));
        }

        $this->flash->success("access_group was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "access_group",
            "action" => "index"
        ));

    }

    /**
     * Saves a access_group edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "access_group",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $access_group = AccessGroup::findFirstByid($id);
        if (!$access_group) {
            $this->flash->error("access_group does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "access_group",
                "action" => "index"
            ));
        }

        $access_group->id = $this->request->getPost("id");
        $access_group->name = $this->request->getPost("name");
        $access_group->rules = $this->request->getPost("rules");
        

        if (!$access_group->save()) {

            foreach ($access_group->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "access_group",
                "action" => "edit",
                "params" => array($access_group->id)
            ));
        }

        $this->flash->success("access_group was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "access_group",
            "action" => "index"
        ));

    }

    /**
     * Deletes a access_group
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $access_group = AccessGroup::findFirstByid($id);
        if (!$access_group) {
            $this->flash->error("access_group was not found");

            return $this->dispatcher->forward(array(
                "controller" => "access_group",
                "action" => "index"
            ));
        }

        if (!$access_group->delete()) {

            foreach ($access_group->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "access_group",
                "action" => "search"
            ));
        }

        $this->flash->success("access_group was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "access_group",
            "action" => "index"
        ));
    }

}
